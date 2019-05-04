<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDayPhoto extends Model
{
    use SoftDeletes;

    public $table = 'job_day_photos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'job_day_id',
        'photo',
        'description',
    ];

    public function getDateAttribute($value) {
        $date = date('d F Y', strtotime($value));

        return $date;
    }

    public function jobDay()
    {
        return $this->belongsTo(JobDay::class, 'job_day_id');
    }

}
