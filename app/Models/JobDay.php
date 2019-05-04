<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDay extends Model
{

    use SoftDeletes;

    public $table = 'job_days';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'job_id',
        'user_mechanic_id',
        'date',
        'days',
        'working_hours',
        'start_working',
        'finish_working',
        'photo_attendance',
        'location_name',
        'location_lat',
        'location_long',
        'location_description',
        'notes',
        'recommendation',
        'status',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function getDateAttribute($value) {
        $date = date('d F Y', strtotime($value));

        return $date;
    }

    public function user_mechanic()
    {
        return $this->belongsTo(User::class, 'user_mechanic_id', 'id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id')->with('user_member', 'job_category');
    }

    public function photos()
    {
        return $this->hasMany(JobDayPhoto::class, 'job_day_id');
    }

}
