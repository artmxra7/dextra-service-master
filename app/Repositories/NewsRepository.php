<?php

namespace App\Repositories;

use App\Models\News;
use InfyOm\Generator\Common\BaseRepository;

class NewsRepository extends BaseRepository
{
	use ApiTrait;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'slug',
        'news_category_id',
        'photo',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return News::class;
    }


    public function api()
    {
    	$_q = $this->model->query();
		if (request('query')) {
			$_q->where('title', 'like', "%". request('query') ."%");
		}
		$_q->latest();

		// Search by query
        return $this->apiTools($_q, 'title');
    }

}
