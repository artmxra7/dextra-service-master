<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use App\Models\JobMechanic;
use App\Models\Job;
use App\Models\User;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class JobMechanicController extends Controller
{
    public function store(Request $request) {
        $req = $request->all();

        if (!$request->has('user_mechanic_id')) {
            $req['user_mechanic_id'] = auth()->id();
        }

        $wasJoined = JobMechanic::where('job_id', $req['job_id'])
                    ->where('user_mechanic_id', $req['user_mechanic_id'])
                    ->exists();

        if ($wasJoined) {
            return $this->sendError('Cannot join again. Already join.', 500);
        }else {
            // Send FCM
            $content = 'New mechanic join, check it now';

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextratama')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            // Custom data will be use to hook action condition in mobile app
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(
              [
                'data' => [
                  'notification_type' => 'CREATE_JOB_MECHANIC'
                ]
              ]
            );

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $adminFCMToken = User::where('role', 'admin')
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

            $downstreamResponse = FCM::sendTo($adminFCMToken, $option, $notification, $data);
            // End FCM

            $job = JobMechanic::create($req);

            if (empty($job)) {
                return $this->sendError('Failed to create job.', 500);
            }
            $jobs = Job::with('job_category', 'user_member', 'job_mechanics')
                      ->where('id', $req['job_id'])
                      ->first();

            return $this->sendResponse($jobs, "success");
        }
    }

    public function update($id, Request $request) {
        $mechanic = JobMechanic::find($id);

        if (empty($mechanic)) {
            return $this->sendError('Job Mechanic Update failed', 500);
        } else {
            $mechanic->update($request->all());
            $content = '';

            if ($request->status == 'approved') {
                $content = 'You are approved !';
            } else {
                $content = 'You are rejected !';
            }

            if ($request->status == 'approved'
            || $request->status == 'rejected') {
                $optionBuiler = new OptionsBuilder();
                $optionBuiler->setTimeToLive(60 * 20);

                $notificationBuilder = new PayloadNotificationBuilder();
                $notificationBuilder->setTitle('Dextra Mechanics')
                                    ->setBody($content)
                                    ->setSound('default')
                                    ->setIcon('ic_launcher')
                                    ->setClickAction('fcm.ACTION.HELLO');

                // Custom data will be use to hook action condition in mobile app
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(
                  [
                    'data' => [
                      'notification_type' => 'UPDATE_JOB_MECHANIC'
                    ]
                  ]
                );

                $option = $optionBuiler->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();

                $mechanicFCMToken = User::find($mechanic->user_mechanic_id)->fcm_token;

                // if(count($mechanicFCMToken) == 0) {
                    $downstreamResponse = FCM::sendTo($mechanicFCMToken, $option, $notification, $data);
                // }
            }

            return $this->sendResponse($mechanic, "success");
        }
    }
}
