<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payment
 * @package App\Models
 * @version May 31, 2017, 7:33 am UTC
 */
class Payment extends Model
{
    use SoftDeletes;

    public $table = 'payments';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'job_id' => 'integer',
        'order_id' => 'integer',
        'user_member_id' => 'integer',
        'bank_name' => 'string',
        'bank_account' => 'string',
        'bank_person_name' => 'string',
        'type' => 'string',
        'status' => 'string',
        'amount' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'job_id' => 'required|numeric',
        'order_id' => 'required|numeric',
        'user_member_id' => 'required|numeric',
        'bank_name' => 'required',
        'bank_account' => 'required',
        'bank_person_name' => 'required',
        'type' => 'required',
        'status' => 'required',
        'amount' => 'numeric'
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
