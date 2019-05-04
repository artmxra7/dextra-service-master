<?php

namespace App\Http\Controllers;

use App\DataTables\OrderSparepartDataTable;
use App\Http\Requests;
use App\Repositories\OrderSparepartRepository;
use App\Models\OrderSparepartCategory;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class OrderSparepartController extends AppBaseController
{
    /** @var  OrderSparepartRepository */
    private $orderSparepartRepository;

    public function __construct(OrderSparepartRepository $orderSparepartRepo)
    {
        $this->orderSparepartRepository = $orderSparepartRepo;
    }

    /**
     * Display a listing of the OrderSparepart.
     *
     * @param OrderSparepartDataTable $orderSparepartDataTable
     * @return Response
     */
    public function index(OrderSparepartDataTable $orderSparepartDataTable)
    {
        return $orderSparepartDataTable->render('order_sparepart.index');
    }

    /**
     * Display the specified OrderSparepart.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $orderSparepart = $this->orderSparepartRepository->findWithoutFail($id);

        if (empty($orderSparepart)) {
            Flash::error('OrderSparepart not found');

            return redirect(route('order_sparepart.index'));
        }

        return view('order_sparepart.show')->with('order_sparepart', $orderSparepart);
    }
}
