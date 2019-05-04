<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\AppBaseController as Controller;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Purchase;
use App\Models\Order;
use App\Models\User;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $req = $request->all();

        $rules = [
            'order_id'   => 'required',
            'file'        => 'required',
            'price'       => 'required',
        ];
        $validator = \Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($req)->withErrors($validator->errors());
        }

        $req['file'] = '';
        if ($request->hasFile('file')) {
            $file         = $request->file;
            $fileDir     = './attachments/offers';
            $fileName    = rand(1000, 9999) . $req['order_id'] . '.' . $file->getClientOriginalExtension();

            $file->move($fileDir, $fileName);

            $req['file'] = $fileName;
        }

        // Send FCM
        $content = 'New offer for your order product';

        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle('Dextra Services')
                            ->setBody($content)
                            ->setSound('default')
                            ->setIcon('ic_launcher')
                            ->setClickAction('fcm.ACTION.HELLO');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(
          [
            'data' => [
              'notification_type' => 'CREATE_PURCHASE'
            ]
          ]
        );

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $order = Order::find($req['order_id']);
        $user_order_fcm_token = User::whereIn('id', array($order['user_member_id'], $order['user_sales_id']))
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

        $downstreamResponse = FCM::sendTo($user_order_fcm_token, $option, $notification, $data);
        // End FCM

        $purchase = Purchase::create($req);
        $order = Order::find($req['order_id']);
        $order->status = 'OFFER_RECEIVED';
        $order->save();

        if ($purchase) {
            return $this->sendResponse($purchase, "success");
        } else {
            return $this->sendResponse('', 'failed');
        }
    }
}
