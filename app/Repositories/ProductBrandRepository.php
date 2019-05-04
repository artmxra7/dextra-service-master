<?php

namespace App\Repositories;

use App\Models\ProductBrand;
use InfyOm\Generator\Common\BaseRepository;

class ProductBrandRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductBrand::class;
    }
}
