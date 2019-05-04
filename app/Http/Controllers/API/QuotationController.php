<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\QuotationRepository;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use App\Models\Quotation;
use App\Models\Job;
use App\Models\User;

class QuotationController extends Controller
{
	/**
	 * @var QuotationRepository
	 */
	private $quotationRepository;

	public function __construct(QuotationRepository $quotationRepository)
	{
		$this->quotationRepository = $quotationRepository;
	}

    public function index()
	{
		$quotations = $this->quotationRepository->getBySales(auth('api')->id());

		return $this->sendResponse($quotations, 'success');
	}

	public function show($id)
	{
		$quotation = $this->quotationRepository->getQuotation([
			'id' => $id
		]);


		if (empty($quotation))
		{

			return $this->sendError('Quotation not found.', 404);

		}

		return $this->sendResponse($quotation, "success");
    }

    public function store(Request $request)
    {
        $req = $request->all();

        $rules = [
            'job_id'      => 'required',
            'file'        => 'required',
            'price'       => 'required',
        ];
        $validator = \Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($req)->withErrors($validator->errors());
        }

        $req['file'] = '';

        if ($request->hasFile('file')) {
            $file        = $request->file;
            $fileDir     = './attachments/offers';
            $fileName    = rand(1000, 9999) . $req['job_id'] . '.' . $file->getClientOriginalExtension();

            $file->move($fileDir, $fileName);

            $req['file'] = $fileName;
        }

		// Send FCM
        $content = 'New quotation for your order job';
        // $dataarray = array(
        //     'data' => null,
        //     'message' => 'New order, check it now',
        // );

        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle('Dextra Services')
                            ->setBody($content)
                            ->setSound('default')
                            ->setIcon('ic_launcher')
                            ->setClickAction('fcm.ACTION.HELLO');

        $dataBuilder = new PayloadDataBuilder();
        // $dataBuilder->addData($dataarray);

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

		$job = Job::find($req['job_id']);
        $user_job_fcm_token = User::whereIn('id', array($job['user_member_id']))
                                        ->get()
                                        ->pluck('fcm_token')
                                        ->toArray();

        $downstreamResponse = FCM::sendTo($user_job_fcm_token, $option, $notification, $data);
        // End FCM

        $quotation = Quotation::create($req);
        $job->status = 'quotation';
        $job->save();

        if ($quotation) {
            return $this->sendResponse($quotation, "success");
        } else {
            return $this->sendResponse('', 'failed');
        }
    }

}
