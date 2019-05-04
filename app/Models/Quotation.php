<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
	use SoftDeletes;

	public $table = 'quotations';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';


	protected $dates = ['deleted_at'];


	public $fillable = [
		'file', 'remarks',  'price', 'job_id'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'file' => 'string',
		'remarks' => 'string',
		'price' => 'integer',
		'job_id' => 'integer'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */

	public static $rules =  [
		'file' => 'required',
		'price' => 'required|numeric'
	];

	public function latest($column = 'id') {
			return $this->orderBy($column, 'desc');
	}

	public function job () {
		return $this->belongsTo(Job::class);
	}
}
