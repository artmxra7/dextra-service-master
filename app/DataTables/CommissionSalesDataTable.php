<?php

namespace App\DataTables;

use App\Models\Commission;
use Form;
use Yajra\Datatables\Services\DataTable;

class CommissionSalesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'commission_sales.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $commission_sales = Commission::join('users', 'commissions.user_id', '=', 'users.id')->where('users.role', 'sales')->select('commissions.*', 'users.name as user_name');

        return $this->applyScopes($commission_sales);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'user' => ['name' => 'user_id', 'data' => 'user_name'],
            'date_commission' => ['name' => 'created_at', 'data' => 'created_at'],
            'description' => ['name' => 'description', 'data' => 'description'],
            'amount' => ['name' => 'amount', 'data' => 'amount'],
            'type' => ['name' => 'type', 'data' => 'type'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'commission_sales';
    }
}
