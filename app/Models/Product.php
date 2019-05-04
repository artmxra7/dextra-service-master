<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models
 * @version April 9, 2017, 2:17 pm UTC
 */
class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'slug',
        'no_product',
        'sn_product',
        'photo',
        'description',
        'price_piece',
        'price_box',
        'is_active',
        'is_stock_available',
        'product_unit_model_id',
        'product_brand_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'no_product' => 'string',
        'sn_product' => 'string',
        'photo' => 'string',
        'description' => 'string',
        'price_piece' => 'integer',
        'price_box' => 'integer',
        'product_unit_model_id' => 'integer',
        'product_brand_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:50',
        'no_product' => 'required|max:50',
        'sn_product' => 'required|max:50',
        'description' => 'required',
        'price_box' => 'required',
        'price_piece' => 'required',
        'is_active' => 'required'
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function productUnitModel()
    {
        return $this->belongsTo(ProductUnitModel::class);
    }
}
