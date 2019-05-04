<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\JobRepository;
use App\Models\Job;
use App\Models\JobMechanic;
use App\Models\Quotation;
use App\Models\User;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class JobController extends Controller
{
    /** @var  JobRepository */
    private $jobRepository;

    public function __construct(JobRepository $jobRepo)
    {
        $this->jobRepository = $jobRepo;
    }

    public function index()
    {
        $role = auth()->user()->role;
        $jobs = null;

        if ($role == 'admin') {
            $jobs = Job::with('job_mechanics', 'job_category')->latest()->get();
        } else {
            $jobs = $this->jobRepository->api(auth('api')->id());
        }

        return $this->sendResponse($jobs, "success");
    }

    public function jobListByStatus(Request $request, $status)
    {
        $user = User::find($request->userID);
        $jobs = [];

        if ($user) {
            if ($status == 'waiting') {
                $joined = JobMechanic::where('user_mechanic_id', $user->id)->pluck('job_id');

                // Get nearby open job list in 10 km
                // but if current mechanic was joined in (still waiting approval or not),
                // jobs outside of radius still showing


                // Get nearby open jobs in 10 km
                $jobsByDistance = Job::with('job_category', 'user_member')
                                ->where('status', $status)
                                ->distance($user->latitude, $user->longitude)
                                ->orderBy('id', 'desc')
                                ->get();

                // Get open jobs joined by current mechanic
                $jobsByJoined = Job::with('job_category', 'user_member')
                                ->where('status', $status)
                                ->whereIn('id', $joined)
                                ->orderBy('id', 'desc')
                                ->get();

                $jobs = $jobsByDistance
                        ->merge($jobsByJoined)
                        ->unique('id')
                        ->sortByDesc('id')
                        ->values()
                        ->all();
            } else {
                // Get approved jobs for current mechanic
                $joined = JobMechanic::where('user_mechanic_id', $user->id)
                          ->where('status', 'approved')
                          ->pluck('job_id');

                // Get jobs joined by current mechanics
                $jobs = Job::with('job_category', 'user_member')
                        ->where('status', $status)
                        ->whereIn('id', $joined)
                        ->orderBy('id', 'desc')
                        ->get();
            }
        }

        return $this->sendResponse($jobs, "success");
    }

    public function show($id)
    {
        $jobs = Job::with([
            'job_category',
            'user_member',
            'job_mechanics',
            'job_days',
            'quotation'
        ])->find($id);

        if (empty($jobs)) {
            return $this->sendError('Order not found.', 404);
        }

        return $this->sendResponse($jobs, "success");
    }

    public function store(Request $request)
    {
        $req = $request->all();
        $req['user_member_id'] = auth()->id();
        $req['status'] = 'waiting';

        $mechanicFCMToken = User::where('role', 'mechanic')
                        ->distance($request->location_lat, $request->location_long)
                        ->get()
                        ->pluck('fcm_token')
                        ->toArray();

        try {
            if(count($mechanicFCMToken) > 0) {
                $data = [
                  'data' => [
                    'notification_type' => 'CREATE_JOB'
                  ]
                ];

                $downstreamResponse = $this->sendNotification(
                    'Dextra Mechanics',
                    'New job around here, check it now',
                    $mechanicFCMToken,
                    $data
                );
            }

            $job = Job::create($req);

            if (empty($job)) {
                return $this->sendError('Failed to create job.', 500);
            }

            return $this->sendResponse($job, "success");
        } catch (\Exception $e) {
            return $this->sendError('Please try again', 500);
        }
    }

    public function update($id, Request $request)
    {
        $req = $request->all();
        $job = Job::with([
            'job_category',
            'user_member',
            'job_mechanics',
            'job_days',
            'quotation'
        ])->find($id);
        $jobMechanicsID = JobMechanic::where('job_id', $id)
                    ->get()
                    ->pluck('user_mechanic_id')
                    ->toArray();

        $data = [
          'data' => [
            'notification_type' => 'UPDATE_JOB'
          ]
        ];

        if ($job) {
            if ($req['fcm_type'] == 'job_approve_reject') {
                $adminFCMToken = User::where('role', 'admin')
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

                try {
                    $downstreamResponse = $this->sendNotification(
                        'Dextratama',
                        'Order job has been approved, check it now',
                        $adminFCMToken,
                        $data
                    );

                    $job->update($req);
                    $job->quotation->update($req);

                    if (empty($job)) {
                        return $this->sendError('Failed to update job.', 500);
                    }

                    return $this->sendResponse($job, "success");
                } catch (\Exception $e) {
                    return $this->sendError('Please try again', 500);
                }
            }
            if ($req['fcm_type'] == 'job_wip') {
                $mechanicFCMToken = User::where('role', 'mechanic')
                                ->whereIn('id', $jobMechanicsID)
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

                if(count($mechanicFCMToken) == 0) {
                    $mechanicFCMToken = User::where('role', 'mechanic')
                                    ->get()
                                    ->pluck('fcm_token')
                                    ->toArray();
                }

                $memberFCMToken = User::where('role', 'member')
                                ->whereIn('id', array($job['user_member_id']))
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

                try {
                    $downstreamResponse = $this->sendNotification(
                        'Dextra Mechanics',
                        'Your job has been approved, check it now',
                        $mechanicFCMToken,
                        $data
                    );

                    $downstreamResponse = $this->sendNotification(
                      'Dextra Services',
                      'Your job has been approved, check it now',
                      $memberFCMToken,
                      $data
                    );

                    $job->update($req);
                    $job->quotation->update($req);

                    if (empty($job)) {
                        return $this->sendError('Failed to update job.', 500);
                    }

                    return $this->sendResponse($job, "success");
                } catch (\Exception $e) {
                    return $this->sendError('Please try again', 500);
                }
            }
            if ($req['fcm_type'] == 'job_close') {
                $mechanicFCMToken = User::where('role', 'mechanic')
                                ->whereIn('id', $jobMechanicsID)
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

                if(count($mechanicFCMToken) == 0) {
                    $mechanicFCMToken = User::where('role', 'mechanic')
                                    ->get()
                                    ->pluck('fcm_token')
                                    ->toArray();
                }

                $memberFCMToken = User::where('role', 'member')
                                ->whereIn('id', array($job['user_member_id']))
                                ->get()
                                ->pluck('fcm_token')
                                ->toArray();

                try {
                    $downstreamResponseMember = $this->sendNotification(
                        'Dextra Services',
                        'Order job has been done, check it now',
                        $memberFCMToken,
                        $data
                    );

                    $downstreamResponseMechanic = $this->sendNotification(
                        'Dextra Mechanics',
                        'Your job has been done, you got commission',
                        $mechanicFCMToken,
                        $data
                    );

                    $job->update($req);
                    $job->quotation->update($req);

                    if (empty($job)) {
                        return $this->sendError('Failed to update job.', 500);
                    }

                    return $this->sendResponse($job, "success");
                } catch (\Exception $e) {
                    return $this->sendError('Please try again', 500);
                }
            }
        } else {
            return $this->sendError('Failed to create job.', 500);
        }

        return $this->sendResponse($job, "success");
    }

    private function sendNotification($title, $message, $tokens, $customData = [])
    {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        // Custom data will be use to hook action condition in mobile app
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($customData);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle($title)
                            ->setBody($message)
                            ->setSound('default')
                            ->setIcon('ic_launcher')
                            ->setClickAction('fcm.ACTION.HELLO');

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse;
    }
}
