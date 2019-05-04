<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Coupon
 * @package App\Models
 * @version April 29, 2017, 10:41 am UTC
 */
class Coupon extends Model
{
    use SoftDeletes;

    public $table = 'coupons';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'description',
        'percent',
        'coupon'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'description' => 'string',
        'coupon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'percent'=> 'required|numeric',
        'coupon' => 'required|max:20'
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }


}
