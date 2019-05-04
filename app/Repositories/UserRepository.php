<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;


class UserRepository extends BaseRepository
{
	use ApiTrait;


    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'city',
        // 'password',
        // 'api_token',
        // 'verification_code',
        // 'remember_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

	public function getUserByType($type, $where = [])
	{
		$_q = $this->model->query();
		$_q->with('company')->where('role', $type);

		if ($where !== null) {
			$_q->where(
				key($where),  current($where)
			);
		}
		$_q->latest();
		return $this->apiTools($_q, 'name');
	}
}
