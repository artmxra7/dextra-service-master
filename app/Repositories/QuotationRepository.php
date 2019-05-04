<?php

namespace App\Repositories;

use App\Models\Quotation;
use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;


class QuotationRepository extends BaseRepository
{
	use ApiTrait;

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Quotation::class;
    }

    public function getQuotation ($condition)
	{
		return Quotation::where($condition)->with('job.user_member.company')->latest()->get();
	}

    public function getBySales ($user_sales_id) {
    	return User::whereHas('jobs', function ($query) use ($user_sales_id) {
			$query->where('user_sales_id', $user_sales_id);
		})
		->with(['jobs' => function ($query) {
			$query->with('quotation');
		}])
		->with('company')
		->latest()
		->get();
	}
}
