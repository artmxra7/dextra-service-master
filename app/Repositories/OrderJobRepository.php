<?php

namespace App\Repositories;

use App\Models\Job;
use InfyOm\Generator\Common\BaseRepository;

class OrderJobRepository extends BaseRepository
{
	use ApiTrait;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'job_category_id',
        'description',
        'location_name',
        'location_lat',
        'location_long',
        'location_description',
        'status',
        'user_member_id',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Job::class;
    }


    public function api()
    {
      	$_q = $this->model->query();
    		if (request('query')) {
    			$_q->where('description', 'like', "%". request('query') ."%");
    		}
				$_q->latest();

		    // Search by query
        return $this->apiTools($_q, 'description');
    }

}
