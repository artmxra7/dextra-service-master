<?php

namespace App\Repositories;

use App\Models\JobCategory;
use InfyOm\Generator\Common\BaseRepository;

class JobCategoryRepository extends BaseRepository
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
        return JobCategory::class;
    }
}
