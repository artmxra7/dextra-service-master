<?php

namespace App\DataTables;

use App\Models\User;
use Form;
use Yajra\Datatables\Services\DataTable;

class UserDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('user_sales', 'users.datatables_user_sales')
            ->addColumn('role', 'users.datatables_role')
            ->addColumn('action', 'users.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = User::query();

        return $this->applyScopes($user);
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
            'name' => ['name' => 'name', 'data' => 'name'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'phone' => ['name' => 'phone', 'data' => 'phone'],
            'user_sales' => ['name' => 'user_sales', 'data' => 'user_sales'],
            'role' => ['name' => 'role', 'data' => 'role'],
            'city' => ['name' => 'city', 'data' => 'city'],
            // 'address' => ['name' => 'address', 'data' => 'address'],
            // 'password' => ['name' => 'password', 'data' => 'password'],
            // 'api_token' => ['name' => 'api_token', 'data' => 'api_token'],
            // 'verification_code' => ['name' => 'verification_code', 'data' => 'verification_code'],
            // 'remember_token' => ['name' => 'remember_token', 'data' => 'remember_token']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users';
    }
}
