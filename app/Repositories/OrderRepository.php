<?php

namespace App\Repositories;

use App\Models\Order;
use InfyOm\Generator\Common\BaseRepository;

class OrderRepository extends BaseRepository
{
    use ApiTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_member_id',
        'user_sales_id',
        'discount_description',
        'discount_percent',
        'discount_coupon',
        'total_price',
        'address',
        'city',
        'notes'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    public function api($user_id)
    {
        $_q = $this->model->query();

        $_q->where('user_member_id', $user_id);

        $_q->with([
            'orderProducts' => function ($q) {
                $q->with('product');
            },
            'purchase'
        ]);
        $_q->latest();

        // Search by query
        return $this->apiTools($_q, 'created_at');
    }

}
