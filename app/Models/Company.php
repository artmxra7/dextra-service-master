<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{

    use SoftDeletes;

    public $table = 'companies';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'sector_business',
        'user_position_title',
        'email',
        'photo',
        'phone',
        'address',
        'user_member_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'                => 'string',
        'sector_business'     => 'string',
        'user_position_title' => 'string',
        'email'               => 'string',
        'photo'               => 'string',
        'phone'               => 'string',
        'address'             => 'string',
        'user_member_id'      => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'                => 'required',
        'sector_business'     => 'required',
        'user_position_title' => 'required',
        'email'               => 'required|email',
        'photo'               => 'required',
        'phone'               => 'required',
        'user_member_id'      => 'integer',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
