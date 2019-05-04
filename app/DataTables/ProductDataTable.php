<?php

namespace App\DataTables;

use App\Models\Product;
use Form;
use Yajra\Datatables\Services\DataTable;

class ProductDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'products.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $products = Product::query();

        return $this->applyScopes($products);
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
            'title' => ['name' => 'title', 'data' => 'title'],
            // 'slug' => ['name' => 'slug', 'data' => 'slug'],
            'no_product' => ['name' => 'no_product', 'data' => 'no_product'],
            // 'sn_product' => ['name' => 'sn_product', 'data' => 'sn_product'],
            // 'photo' => ['name' => 'photo', 'data' => 'photo'],
            // 'description' => ['name' => 'description', 'data' => 'description'],
            'price_piece' => ['name' => 'price_piece', 'data' => 'price_piece'],
            'price_box' => ['name' => 'price_box', 'data' => 'price_box'],
            'is_active' => ['name' => 'is_active', 'data' => 'is_active'],
            // 'product_unit_model_id' => ['name' => 'product_unit_model_id', 'data' => 'product_unit_model_id'],
            // 'product_brand_id' => ['name' => 'product_brand_id', 'data' => 'product_brand_id']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products';
    }
}
