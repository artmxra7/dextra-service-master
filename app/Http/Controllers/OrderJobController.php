<?php

namespace App\Http\Controllers;

use App\DataTables\OrderJobDataTable;
use App\Http\Requests;
use App\Repositories\OrderJobRepository;
use App\Models\OrderJobCategory;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class OrderJobController extends AppBaseController
{
    /** @var  OrderJobRepository */
    private $orderJobRepository;

    public function __construct(OrderJobRepository $orderJobRepo)
    {
        $this->orderJobRepository = $orderJobRepo;
    }

    /**
     * Display a listing of the OrderJob.
     *
     * @param OrderJobDataTable $orderJobDataTable
     * @return Response
     */
    public function index(OrderJobDataTable $orderJobDataTable)
    {
        return $orderJobDataTable->render('order_job.index');
    }

    /**
     * Display the specified OrderJob.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $orderJob = $this->orderJobRepository->findWithoutFail($id);

        if (empty($orderJob)) {
            Flash::error('OrderJob not found');

            return redirect(route('order_job.index'));
        }

        return view('order_job.show')->with('order_job', $orderJob);
    }
}
