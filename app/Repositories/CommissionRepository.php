<?php

namespace App\Repositories;

use App\Models\Commission;
use InfyOm\Generator\Common\BaseRepository;

class CommissionRepository extends BaseRepository
{
	use ApiTrait;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'job_id',
        'order_id',
        'description',
        'amount',
        'type',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Commission::class;
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
