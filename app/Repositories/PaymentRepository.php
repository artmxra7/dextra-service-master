<?php

namespace App\Repositories;

use App\Models\Payment;
use InfyOm\Generator\Common\BaseRepository;

class PaymentRepository extends BaseRepository
{
    use ApiTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'job_id',
        'order_id',
        'user_member_id',
        'bank_name',
        'bank_account',
        'bank_person_name',
        'type',
        'status',
        'amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Payment::class;
    }

    public function api($user_id)
    {
        $_q = $this->model->query();
        $_q->latest();

        // Search by query
        return $this->apiTools($_q, 'created_at');
    }

}
