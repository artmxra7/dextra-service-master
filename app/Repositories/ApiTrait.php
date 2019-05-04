<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Created by PhpStorm.
 * User: fachri
 * Date: 5/1/2017
 * Time: 11:01 PM
 */

trait ApiTrait {

	/**
	 * @param Builder $query
	 * @param string $orderColumn
	 * @return Collection
	 */
	public function apiTools(Builder $query, $orderColumn = 'title')
	{

		$perPage = 10;

		// Limit data each page
		$query->take(
			$perPage = request('per_page') ?
				request('per_page'):
				$perPage
		);

		// Get data from current page
		$query->skip(
			request('page') ?
				(request('page') - 1) * $perPage :
				0
		);

		// Sort data
		$query->orderBy(
			request('column') ? request('column') : $orderColumn,
			request('sort') ? request('sort') : 'asc'
		);

		return $query->get();
	}
}
