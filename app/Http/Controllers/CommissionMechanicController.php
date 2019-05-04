<?php

namespace App\Http\Controllers;

use App\DataTables\CommissionMechanicDataTable;
use App\Http\Requests;
use App\Repositories\CommissionRepository;
use App\Models\CommissionMechanicCategory;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CommissionMechanicController extends AppBaseController
{
    /** @var  CommissionRepository */
    private $commissionRepository;

    public function __construct(CommissionRepository $commissionMechanicRepo)
    {
        $this->commissionRepository = $commissionMechanicRepo;
    }

    /**
     * Display a listing of the CommissionMechanic.
     *
     * @param CommissionMechanicDataTable $commissionMechanicDataTable
     * @return Response
     */
    public function index(CommissionMechanicDataTable $commissionMechanicDataTable)
    {
        return $commissionMechanicDataTable->render('commission_mechanic.index');
    }

    /**
     * Display the specified CommissionMechanic.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $commissionMechanic = $this->commissionRepository->findWithoutFail($id);

        if (empty($commissionMechanic)) {
            Flash::error('CommissionMechanic not found');

            return redirect(route('commission_mechanic.index'));
        }

        return view('commission_mechanic.show')->with('commission_mechanic', $commissionMechanic);
    }
}
