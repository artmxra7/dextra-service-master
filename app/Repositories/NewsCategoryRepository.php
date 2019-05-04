<?php

namespace App\Repositories;

use App\Models\NewsCategory;
use InfyOm\Generator\Common\BaseRepository;

class NewsCategoryRepository extends BaseRepository
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
        return NewsCategory::class;
    }
}
