<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCategory extends Model
{
    use SoftDeletes;

    public $table = 'job_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
    ];

    /**
    * The attributes that should be casted to native types.
    *
    * @var array
    */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    /**
    * Validation rules
    *
    * @var array
    */
    public static $rules = [
        'name' => 'required',
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function job()
    {
        return $this->hasOne(Job::class, 'job_category_id');
    }
}
