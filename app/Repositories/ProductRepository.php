<?php

namespace App\Repositories;

use DB;
use App\Models\Product;
use InfyOm\Generator\Common\BaseRepository;

class ProductRepository extends BaseRepository
{

    use ApiTrait;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'slug',
        'no_product',
        'sn_product',
        'photo',
        'description',
        'price_piece',
        'price_box',
        'is_active',
        'is_stock_available',
        'product_unit_model_id',
        'product_brand_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    public function api()
    {
        $_q = $this->model->query();
        $_q->with('productBrand')->with('productUnitModel');
        if (request('query')) {
            $_q->where('title', 'like', "%". request('query') ."%");
        }
        $_q->latest();

        // Search by query
        return $this->apiTools($_q, 'title');
    }

    public function getByUnitModel($unit_model)
    {
        $_q = $this->model->query();
        $_q->with('productBrand')->with('productUnitModel');
        $_q->where('product_unit_model_id', $unit_model);

        if (request('query')) {
            $_q->where('title', 'like', "%". request('query') ."%");
        }
        $_q->latest();

        // Search by query
        return $this->apiTools($_q, 'title');
    }
}
