<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\OrderRepository;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Company;
use App\Models\Payment;
use App\Models\User;

class OrderController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    public function index()
    {
        $user = User::find(auth('api')->id());
        $orders = [];

        switch ($user->role) {
            case 'admin':
                $orders = Order::with([
                        'orderProducts' => function ($q) {
                            $q->with('product');
                        },
                        'purchase'
                    ])
                    ->latest()
                    ->get();
                break;
            case 'member':
                $orders = Order::where('user_member_id', $user->id)
                    ->with([
                        'orderProducts' => function ($q) {
                            $q->with('product');
                        },
                        'purchase'
                    ])
                    ->latest()
                    ->get();
                break;
            case 'sales':
                $orders = Order::where('user_sales_id', $user->id)
                    ->with([
                        'orderProducts' => function ($q) {
                            $q->with('product');
                        },
                        'purchase',
                        'user_member'
                    ])
                    ->latest()
                    ->get();
                break;
            default:
                break;
        }

        return $this->sendResponse($orders, "success");
    }

    public function show($id)
    {
        $orders = $this->orderRepository->with([
            'purchase',
            'payment',
            'user_member',
            'orderProducts' => function ($q1) {
                $q1->with([
                    'product' => function ($q2) {
                        $q2->with('productBrand', 'productUnitModel');
                    }
                ]);
            }
        ])->findWithoutFail($id);

        if (empty($orders)) {
            return $this->sendError('Order not found.', 404);
        }

        return $this->sendResponse($orders, "success");
    }

    public function store(Request $request)
    {
        $req = $request->all();
        $order = Order::create($req['shipping']);

        if ($order) {
            // Inserting company data if not has
            $company = Company::where('user_member_id', $request->user_member_id)->first();

            if ($company) {
                $company->update($req['company']);
            } else {
                $company = Company::create($req['company']);
            }

            // Inserting personal data if not has
            $user = User::find($request->user_member_id);

            if ($user) {
                $req['personal']['company_id'] = $company->id;
                $user->update($req['personal']);
            }

            // Inserting items from cart
            $items = [];
            foreach ($request->items as $item) {
                $item = (object) $item;
                $price = $item->selected_type == 'pcs' ? $item->price_piece : $item->price_box;
                $isCartRequest = !$item->price_piece && !$item->price_box;

                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_title' => $item->title,
                    'product_brand' => $item->product_brand['name'],
                    'product_type' => $item->selected_type,
                    'no_product' => $item->no_product,
                    'sn_product' => $item->sn_product,
                    'price' => $isCartRequest ? 0 : $price,
                    'qty' => $item->quantity,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ];
            }

            // Send FCM
            $content = 'New order, check it now';

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            // Custom data will be use to hook action condition in mobile app
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(
              [
                'data' => [
                  'notification_type' => 'CREATE_ORDER'
                ]
              ]
            );

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextra Admin')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $users_admin_fcm_token = User::where('role', 'admin')->get()->pluck('fcm_token')->toArray();

            if(count($users_admin_fcm_token) > 0) {
                $downstreamResponse = FCM::sendTo($users_admin_fcm_token, $option, $notification, $data);
            }
            // End FCM

            OrderProduct::insert($items);

            $order = Order::with('orderProducts')->find($order->id);
        }

        if (empty($order)) {
            return $this->sendError('Order failed.', 500);
        }

        return $this->sendResponse($order, "success");
    }

    public function update($id, Request $request)
    {
        $req = $request->all();
        $order = $this->orderRepository->with([
            'purchase',
            'payment',
            'user_member',
            'orderProducts' => function ($q1) {
                $q1->with([
                    'product' => function ($q2) {
                        $q2->with('productBrand', 'productUnitModel');
                    }
                ]);
            }
        ])->findWithoutFail($id);

        if ($order) {
            // Send FCM
            if ($req['status'] == 'OFFER_AGREED' or
                $req['status'] == 'OFFER_REJECTED' or
                $req['status'] == 'DELIVERY_RECEIVED') {

                $content = 'Order status changed';
                if ($req['status'] == 'OFFER_AGREED') {
                    $content = 'An order product agreed by customer';
                }
                if ($req['status'] == 'OFFER_REJECTED') {
                    $content = 'An order product rejected by customer';
                }
                if ($req['status'] == 'DELIVERY_RECEIVED') {
                    $content = 'An order product has been received by customer';
                }

                // $dataarray = array(
                //     'data' => null,
                //     'message' => 'New order, check it now',
                // );

                $optionBuiler = new OptionsBuilder();
                $optionBuiler->setTimeToLive(60 * 20);

                // Custom data will be use to hook action condition in mobile app
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(
                  [
                    'data' => [
                      'notification_type' => 'UPDATE_ORDER'
                    ]
                  ]
                );

                $notificationBuilder = new PayloadNotificationBuilder();
                $notificationBuilder->setTitle('Dextra Admin')
                                    ->setBody($content)
                                    ->setSound('default')
                                    ->setIcon('ic_launcher')
                                    ->setClickAction('fcm.ACTION.HELLO');

                $option = $optionBuiler->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();

                $users_admin_fcm_token = User::where('role', 'admin')->get()->pluck('fcm_token')->toArray();

                $downstreamResponse = FCM::sendTo($users_admin_fcm_token, $option, $notification, $data);
            }
            if ($req['status'] == 'DELIVERY_PROCESS' or
                $req['status'] == 'ORDER_FINISHED') {

                $content = 'Order status changed';
                if ($req['status'] == 'DELIVERY_PROCESS') {
                    $content = 'Order products are in the process of shipping';
                }
                if ($req['status'] == 'ORDER_FINISHED') {
                    $content = 'An order product has been finished';
                }

                // $dataarray = array(
                //     'data' => null,
                //     'message' => 'New order, check it now',
                // );

                $optionBuiler = new OptionsBuilder();
                $optionBuiler->setTimeToLive(60 * 20);

                // Custom data will be use to hook action condition in mobile app
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(
                  [
                    'data' => [
                      'notification_type' => 'UPDATE_ORDER'
                    ]
                  ]
                );

                $notificationBuilder = new PayloadNotificationBuilder();
                $notificationBuilder->setTitle('Dextra Services')
                                    ->setBody($content)
                                    ->setSound('default')
                                    ->setIcon('ic_launcher')
                                    ->setClickAction('fcm.ACTION.HELLO');

                $option = $optionBuiler->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();

                $user_order_fcm_token = User::whereIn('id', array($order['user_member_id'], $order['user_sales_id']))->get()
                                        ->pluck('fcm_token')
                                        ->toArray();

                $downstreamResponse = FCM::sendTo($user_order_fcm_token, $option, $notification, $data);
            }
            // End FCM

            $order->update($request->all());

            switch ($req['status']) {
                case 'OFFER_AGREED':
                case 'OFFER_REJECTED':
                    $order->purchase->update($request->all());
                    break;
                default:
                    break;
            }
        }

        if (empty($order)) {
            return $this->sendError('Order failed.', 500);
        }

        return $this->sendResponse($order, "success");
    }
}
