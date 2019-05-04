<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\CreateWithdrawRequest;
use App\Repositories\WithdrawRepository;
use App\Http\Controllers\AppBaseController as Controller;
use Intervention\Image\ImageManagerStatic as Image;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Withdraw;
use App\Models\User;

class WithdrawController extends Controller
{
    /**
     * Resize a photo
     *
     * @param  integer $width        Image's width
     * @param  integer $height       Image's height
     * @param  string  $rawfile      RAW photo file location include filename
     * @param  string  $resultfile   Result photo file location include filename
     * @return void
     */

    private function resizePhoto($width, $height, $rawfile, $resultfile)
    {
        // Resize image file by aspect ratio
        Image::make($rawfile)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($resultfile);
    }

    /**
     * @var WithdrawRepository
     */
    private $withdrawRepository;

    /**
     * WithdrawController constructor.
     * @param WithdrawRepository $withdrawRepository
     */
    public function __construct(WithdrawRepository $withdrawRepository)
    {
        $this->withdrawRepository = $withdrawRepository;
    }

    public function index()
    {
        $withdraws = $this->withdrawRepository->api(auth('api')->id());
        return $this->sendResponse($withdraws, "success");
    }

    public function withdrawListByStatus($status) {
        $jobs  = Withdraw::with('user')
                  ->where('status', $status)
                  ->orderBy('id', 'desc')
                  ->get();
        return $this->sendResponse($jobs, "success");
    }

    public function show($id)
    {
        $withdraw = Withdraw::with('user')->find($id);

        if (empty($withdraw)) {
            return $this->sendError('Withdraw request failed.', 404);
        }

        return $this->sendResponse($withdraw, "Withdraw request success.");
    }

    public function store(CreateWithdrawRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth('api')->id();
        $input['status'] = 'waiting';

        $withdraw = $this->withdrawRepository->create($input);

        if (empty($withdraw)) {
            return $this->sendError('Withdraw request failed.', 404);
        } else {
            $content = 'There is a new withdraw, Check it now !';

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextratama')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();

            $adminFCMToken = User::where('role', 'admin')
                            ->get()
                            ->pluck('fcm_token')
                            ->toArray();

            $downstreamResponse = FCM::sendTo($adminFCMToken, $option, $notification);
        }

        return $this->sendResponse([], "Withdraw request success.");
    }

    public function update($id, Request $request)
    {
        $req = $request->all();
        $withdraw = Withdraw::find($id);

        if ($withdraw) {
            if ($request->hasFile('photo')) {
                $photo = $request->photo;
                $timestamp = date_timestamp_get(date_create());
                $extension = $photo->extension();
                $title = 'withdraw_'.$timestamp;
                $path = './attachments/withdraws';
                $rawFile = $title.'.'.$extension;
                $thumbnail = $title.'_thumbnail.'.$extension;

                $photo->move($path, $rawFile);
                $this->resizePhoto(300, null, $path.'/'.$rawFile, $path.'/'.$thumbnail);
                $req['photo'] = $rawFile;

                // Send FCM Notification
                $content = 'Your withdraw has been approved !';

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

                $salesFCMToken = User::find($withdraw->user_id)->fcm_token;

                $downstreamResponse = FCM::sendTo($salesFCMToken, $option, $notification);
            }

            $withdraw->update($req);

            return $this->sendResponse($withdraw, "Withdraw request success.");
        } else {
            return $this->sendError('Withdraw request failed.', 404);
        }
    }
}
