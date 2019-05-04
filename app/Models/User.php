<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package App\Models
 * @version April 9, 2017, 1:19 pm UTC
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'city',
        'user_sales_id',
        'company_id',
        'api_token',
        'fcm_token',
        'verification_code',
        'latitude',
        'longitude',
        'remember_token'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verification_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_sales_id' => 'integer',
        'company_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'role' => 'string',
        'city' => 'string',
        'api_token' => 'string',
        'verification_code' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */

    public static $rules =  [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
        'role' => 'required',
        'city' => 'required'
    ];

    protected $appends = ['user_sales_name'];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function userSales()
    {
        return $this->hasOne(User::class, 'user_sales_id', 'id');
    }

    public function getUserSalesNameAttribute()
    {
        $user = User::find($this->user_sales_id);
        if ($user) {
            return $user->name;
        }else {
            return '-';
        }
    }

    public function order_sales()
    {
        return $this->hasOne(Order::class, 'user_sales_id');
    }

    public function order_member()
    {
        return $this->hasOne(Order::class, 'user_member_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'user_member_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'user_id');
    }

    public function scopeDistance($query, $latitude, $longitude, $radius = 10) {
        $unit = 6378.10;
        $radius = (double) $radius;

        return $query->having('distance','<=',$radius)
        ->select(\DB::raw("*,
            ($unit * ACOS(COS(RADIANS($latitude))
                * COS(RADIANS(latitude))
                * COS(RADIANS($longitude) - RADIANS(longitude))
                + SIN(RADIANS($latitude))
                * SIN(RADIANS(latitude)))) AS distance")
        )->orderBy('distance','asc');
    }
}
