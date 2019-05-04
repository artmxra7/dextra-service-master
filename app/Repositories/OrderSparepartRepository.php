<?php

namespace App\Repositories;

use App\Models\Order;
use InfyOm\Generator\Common\BaseRepository;

class OrderSparepartRepository extends BaseRepository
{
	use ApiTrait;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_member_id',
        'user_member_id',
        'discount_description',
        'discount_percent',
        'discount_coupon',
        'total_price',
        'address',
        'city',
        'notes',
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }


    public function api()
    {
      	$_q = $this->model->query();
    		if (request('query')) {
    			$_q->where('notes', 'like', "%". request('query') ."%");
    		}
				$_q->latest();

		    // Search by query
        return $this->apiTools($_q, 'notes');
    }

}
