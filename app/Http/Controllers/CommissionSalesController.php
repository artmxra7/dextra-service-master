<?php

namespace App\Http\Controllers;

use App\DataTables\CommissionSalesDataTable;
use App\Http\Requests;
use App\Repositories\CommissionRepository;
use App\Models\CommissionSalesCategory;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CommissionSalesController extends AppBaseController
{
    /** @var  CommissionRepository */
    private $commissionRepository;

    public function __construct(CommissionRepository $commissionSalesRepo)
    {
        $this->commissionRepository = $commissionSalesRepo;
    }

    /**
     * Display a listing of the CommissionSales.
     *
     * @param CommissionSalesDataTable $commissionSalesDataTable
     * @return Response
     */
    public function index(CommissionSalesDataTable $commissionSalesDataTable)
    {
        return $commissionSalesDataTable->render('commission_sales.index');
    }

    /**
     * Display the specified CommissionSales.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $commissionSales = $this->commissionRepository->findWithoutFail($id);

        if (empty($commissionSales)) {
            Flash::error('CommissionSales not found');

            return redirect(route('commission_sales.index'));
        }

        return view('commission_sales.show')->with('commission_sales', $commissionSales);
    }
}
