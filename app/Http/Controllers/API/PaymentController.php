<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\PaymentRepository;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;

class PaymentController extends Controller
{
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepository = $paymentRepo;
    }

    public function index()
    {
        $payments  = $this->paymentRepository->api(auth('api')->id());
        return $this->sendResponse($payments, "success");
    }


    public function show($id)
    {
        $payments = $this->paymentRepository->with([
            'purchase',
            'orderProducts' => function ($q) {
                $q->with('product');
            }
        ])->findWithoutFail($id);

        if (empty($payments)) {
            return $this->sendError('Payment not found.', 404);
        }

        return $this->sendResponse($payments, "success");
    }

    public function store(Request $request)
    {
        $payment = Payment::create($request->all());

        // Send FCM
        $content = 'Customer transfer payment for order product';

        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle('Dextra Admin')
                            ->setBody($content)
                            ->setSound('default')
                            ->setIcon('ic_launcher')
                            ->setClickAction('fcm.ACTION.HELLO');

        // Custom data will be use to hook action condition in mobile app
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(
          [
            'data' => [
              'notification_type' => 'CREATE_PAYMENT'
            ]
          ]
        );

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $users_admin_fcm_token = User::where('role', 'admin')->get()->pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($users_admin_fcm_token, $option, $notification, $data);
        // End FCM

        if (empty($payment)) {
            return $this->sendError('Payment failed.', 500);
        } else {
            $order = Order::find($request->order_id);
            $order->update([ 'status' => 'WAITING_PAYMENT_CONFIRMED' ]);
        }

        return $this->sendResponse($payment, "success");
    }

    public function update($id, Request $request)
    {
        $payment = Payment::find($id);

        if (empty($payment)) {
            return $this->sendError('Payment failed.', 500);
        } else {
            // Send FCM
            $content = 'Payment confirmed by Dextratama, your order will be process';

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextra Services')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();

            $users_member_fcm_token = User::where('id', $payment->user_member_id)->get()->pluck('fcm_token')->toArray();

            $downstreamResponse = FCM::sendTo($users_member_fcm_token, $option, $notification);
            // End FCM

            $payment->update([ 'status' => $request->status ]);
        }

        return $this->sendResponse($payment, "success");
    }
}
