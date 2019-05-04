<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @version May 31, 2017, 7:33 am UTC
 */
class Order extends Model
{
    use SoftDeletes;

    public $table = 'orders';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_member_id',
        'user_sales_id',
        'discount_description',
        'discount_percent',
        'discount_coupon',
        'total_price',
        'address',
        'city',
        'notes',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_member_id' => 'integer',
        'user_sales_id' => 'integer',
        'discount_description' => 'string',
        'discount_coupon' => 'string',
        'address' => 'string',
        'city' => 'string',
        'notes' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_member_id' => 'required|numeric',
        'discount_percent' => 'numeric',
        'total_price' => 'required',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }

    public function user_member() {
        return $this->belongsTo(User::class, 'user_member_id');
    }

    public function user_sales() {
        return $this->belongsTo(User::class, 'user_sales_id');
    }

    public function purchase() {
        return $this->hasOne(Purchase::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'job_id');
    }

}
