<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{

    use SoftDeletes;

    public $table = 'jobs';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_member_id',
        'job_category_id',
        'description',
        'location_name',
        'location_lat',
        'location_long',
        'location_description',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_member_id' => 'integer',
        'job_category_id' => 'integer',
        'description' => 'string',
        'location_name' => 'string',
        'location_lat' => 'string',
        'location_long' => 'string',
        'location_description' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */

    public static $rules = [
        'user_member_id' => 'required|numeric',
        'job_category_id' => 'required',
        'description' => 'required',
        'location_name' => 'required',
        'location_lat' => 'required',
        'location_long' => 'required',
        'location_description' => 'required',
        'status' => 'required'
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'job_id');
    }

    public function job_mechanics()
    {
        return $this->hasMany(JobMechanic::class, 'job_id')->with('user');
    }

    public function job_days()
    {
        return $this->hasMany(JobDay::class, 'job_id');
    }

    public function user_member()
    {
        return $this->belongsTo(User::class, 'user_member_id', 'id')->with('company');
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function scopeDistance($query, $latitude, $longitude, $radius = 10) {
        $unit = 6378.10;
        $radius = (double) $radius;

        return $query->having('distance','<=',$radius)
        ->select(\DB::raw("*,
            ($unit * ACOS(COS(RADIANS($latitude))
                * COS(RADIANS(location_lat))
                * COS(RADIANS($longitude) - RADIANS(location_long))
                + SIN(RADIANS($latitude))
                * SIN(RADIANS(location_lat)))) AS distance")
        )->orderBy('distance','asc');
    }
}
