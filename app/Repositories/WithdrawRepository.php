<?php

namespace App\Repositories;

use App\Models\Withdraw;
use InfyOm\Generator\Common\BaseRepository;

class WithdrawRepository extends BaseRepository
{
    use ApiTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'amount',
        'bank_name',
        'bank_account',
        'bank_person_name',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Withdraw::class;
    }

    public function api($id)
    {
        $_q = $this->model->query();
        $_q->where('user_id', $id);
        $_q->latest();

        return $this->apiTools($_q, 'created_at');
    }
}
