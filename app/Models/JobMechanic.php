<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMechanic extends Model
{
    public $table = 'job_mechanics';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'job_id',
        'user_mechanic_id',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_mechanic_id');
    }
}
