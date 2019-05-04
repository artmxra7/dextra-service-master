<?php

namespace App\Repositories;

use App\Models\Company;
use InfyOm\Generator\Common\BaseRepository;

class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'sector_business',
        'user_position_title',
        'email',
        'photo',
        'phone',
        'address',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }
}
