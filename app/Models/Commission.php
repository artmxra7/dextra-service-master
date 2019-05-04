<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{

	use SoftDeletes;

	public $table = 'commissions';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';


	protected $dates = ['deleted_at'];


	public $fillable = [
        'job_id',
        'order_id',
		'user_id',
		'description',
        'amount',
        'type'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'description' => 'string',
        'job_id'      => 'integer',
        'order_id'    => 'integer',
		'user_id'     => 'integer',
        'amount'      => 'integer',
        'type'        => 'string',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
	];

	public function latest($column = 'id') {
			return $this->orderBy($column, 'desc');
	}

	public function job() {
		return $this->belongsTo(Job::class, 'job_id');
	}

	public function order() {
		return $this->belongsTo(Order::class, 'order_id');
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
}
