<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Purchase
 * @package App\Models
 * @version May 31, 2017, 7:33 am UTC
 */
class Purchase extends Model
{
    use SoftDeletes;

    public $table = 'purchases';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'order_id',
        'file',
        'remarks',
        'price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'file' => 'string',
        'remarks' => 'string',
        'price' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'required|numeric',
        'file' => 'required',
        'price' => 'required|numeric',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

}
