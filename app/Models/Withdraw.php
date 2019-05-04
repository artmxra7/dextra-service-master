<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{

    use SoftDeletes;

    public $table = 'withdraws';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'amount',
        'bank_name',
        'bank_account',
        'bank_person_name',
        'photo',
        'status'
    ];

    protected $appends = ['date'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bank_name'                => 'string',
        'bank_account'     => 'string',
        'bank_person_name' => 'string',
        'status' => 'string',
        'amount' => 'integer',
        'user_id'      => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bank_name' => 'required',
        'bank_account' => 'required',
        'bank_person_name' => 'required',
        'amount' => 'required|numeric'
    ];

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function getDateAttribute($value) {
        $date = date('d F Y', strtotime($this->created_at));

        return $date;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
