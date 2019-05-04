<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @version May 31, 2017, 7:33 am UTC
 */
class OrderProduct extends Model
{
    use SoftDeletes;

    public $table = 'order_products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'order_id',
        'product_id',
        'product_title',
        'product_brand',
        'product_type',
        'no_product',
        'sn_product',
        'price',
        'qty'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'integer',
        'qty' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'numeric|required',
        'product_id' => 'numeric|required',
        'price' => 'numeric|required',
        'qty' => 'numeric|required',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
