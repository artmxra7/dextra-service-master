<?php

namespace App\Repositories;

use App\Models\Job;
use InfyOm\Generator\Common\BaseRepository;


class JobRepository extends BaseRepository
{
	use ApiTrait;

	protected $fieldSearchable = [
        'user_member_id',
        'title',
        'description',
        'location_name',
        'location_lat',
        'location_long',
        'location_description',
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Job::class;
    }

    public function api($user_id)
    {
        $_q = $this->model->query();

        $_q->where('user_member_id', $user_id);
        $_q->with('job_category');
				$_q->latest();

        // Search by query
        return $this->apiTools($_q, 'created_at');
    }
}
