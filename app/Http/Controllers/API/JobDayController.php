<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use Intervention\Image\ImageManagerStatic as Image;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Job;
use App\Models\JobDay;
use App\Models\JobDayPhoto;
use App\Models\User;

class JobDayController extends Controller
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

    public function jobDayScheduleList($status)
    {
        $job_days  = JobDay::with('job')
                    ->where('user_mechanic_id', auth('api')->id())
                    ->where('status', $status)
                    ->get();

        return $this->sendResponse($job_days, "success");
    }

    public function show($id)
    {
      $job_days = JobDay::with('job', 'photos', 'user_mechanic')->find($id);

      return $this->sendResponse($job_days, "success");
    }

    public function getJobDay($id)
    {
        $job_days  = JobDay::where('id', $id)->first();

        return $this->sendResponse($job_days, "success");
    }

    public function store(Request $request)
    {
        $jobDay = JobDay::create($request->all());

        if (empty($jobDay)) {
            return $this->sendError('Job day create failed.', 500);
        } else {
            $jobDay = JobDay::find($jobDay->id);

            return $this->sendResponse($jobDay, "success");
        }
    }

    public function storeMass(Request $request)
    {
        $dateFrom = date_create($request->date_from);
        $dateUntil = date_create($request->date_until);
        $diffDay = date_diff($dateFrom, $dateUntil);
        $totalDay = $diffDay->format("%a");
        $job_days = JobDay::where('job_id', $request->job_id)->orderBy('created_at', 'desc')->first();
        $nowDay = 0;
        if ($job_days) {
            $nowDay = $job_days->days;
        }

        $date = $request->date_from;
        for ($i=0; $i <= $totalDay; $i++) {
            $nowDay++;

            $data = array(
              'date' => $date,
              'job_id' => $request->job_id,
              'user_mechanic_id' => $request->user_mechanic_id,
              'days' => $nowDay,
            );
            $jobDay = JobDay::create($data);

            $date = strtotime("+1 day", strtotime($date));
            $date = date("Y-m-d", $date);
        }
        $jobs = Job::with([
            'job_category',
            'user_member',
            'job_mechanics',
            'job_days',
            'quotation'
        ])->find($request->job_id);

        return $this->sendResponse($jobs, "success");
    }

    public function update($id, Request $request)
    {
        $jobDay = JobDay::find($id);

        if ($jobDay) {
            $jobDay->update($request->all());
        } else {
            return $this->sendError('Job day update failed.', 500);
        }

        $jobs = Job::with([
            'job_category',
            'user_member',
            'job_mechanics',
            'job_days',
            'quotation'
        ])->find($request->job_id);

        return $this->sendResponse($jobs, "success");
    }

    public function startWorking($id, Request $request)
    {
        $req = $request->all();
        $job_days = JobDay::find($id);

        if (empty($job_days)) {
            return $this->sendError('Job day failed.', 500);
        } else {
            if ($request->hasFile('photo_attendance')) {
                $photo_attendance = $request->photo_attendance;
                $timestamp = date_timestamp_get(date_create());
                $extension = $photo_attendance->getClientOriginalExtension();
                $title = 'attendance_'.$timestamp;
                $path = './attachments/attendances';
                $rawFile = $title.'.'.$extension;
                $thumbnail = $title.'_thumbnail.'.$extension;

                $photo_attendance->move($path, $rawFile);
                // $this->resizePhoto(300, null, $path.'/'.$rawFile, $path.'/'.$thumbnail);
                $req['photo_attendance'] = $rawFile;
            }

            // Send FCM
            $content = 'There\'s a mechanic who start working';
            // $dataarray = array(
            //     'data' => null,
            //     'message' => 'New order, check it now',
            // );

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextratama')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            $dataBuilder = new PayloadDataBuilder();
            // $dataBuilder->addData($dataarray);

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $user_admin_fcm_token = User::where('role', 'admin')
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

            $downstreamResponse = FCM::sendTo($user_admin_fcm_token, $option, $notification, $data);
            // End FCM

            $job_days->update($req);
        }

        return $this->sendResponse($job_days, "success");
    }

    public function finishWorking($id, Request $request)
    {
        $req = $request->all();
        $job_day = JobDay::find($id);

        if (empty($job_day)) {
            return $this->sendError('Job day failed.', 500);
        } else {
            // Send FCM
            $content = 'There\'s a mechanic who finished job day';
            // $dataarray = array(
            //     'data' => null,
            //     'message' => 'New order, check it now',
            // );

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle('Dextratama')
                                ->setBody($content)
                                ->setSound('default')
                                ->setIcon('ic_launcher')
                                ->setClickAction('fcm.ACTION.HELLO');

            $dataBuilder = new PayloadDataBuilder();
            // $dataBuilder->addData($dataarray);

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $user_admin_fcm_token = User::where('role', 'admin')
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

            $downstreamResponse = FCM::sendTo($user_admin_fcm_token, $option, $notification, $data);
            // End FCM

            $job_day->update($req);
        }

        return $this->sendResponse($req, "success");
    }

    public function checkMechanicIsWIP()
    {
        $job_days = JobDay::with('job', 'photos')
                ->where('user_mechanic_id', auth('api')->id())
                ->where('status', 'wip')
                ->get();

        if (empty($job_days)) {
            return $this->sendError('Job day failed.', 500);
        } else {
            return $this->sendResponse($job_days, "success");
        }
    }
    public function destroy($id)
    {
      $job_day = JobDay::find($id);
      $jobDayID = $job_day->job_id;

      if (empty($job_day)) {
          return $this->sendError('Job day delete failed.', 500);
      }else {
          $job_day->forceDelete();
      }
      $jobDaysID = JobDay::where('job_id', $jobDayID)->orderBy('created_at', 'asc')->pluck('id');

      $dayIndex = 1;
      for ($i=0; $i < count($jobDaysID); $i++) {
        $job_day = JobDay::find($jobDaysID[$i]);
        $job_day->days = $dayIndex;
        $job_day->save();

        $dayIndex++;
      }


      $jobs = Job::with([
          'job_category',
          'user_member',
          'job_mechanics',
          'job_days',
          'quotation'
      ])->find($jobDayID);

      return $this->sendResponse($jobs, "success");
    }
}
