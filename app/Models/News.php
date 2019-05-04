<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class News
 * @package App\Models
 * @version April 9, 2017, 1:11 pm UTC
 */
class News extends Model
{
    use SoftDeletes;

    public $table = 'news';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $appends = ['content_summary', 'date'];


    public $fillable = [
        'title',
        'slug',
        'news_category_id',
        'photo',
        'content'
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
        'news_category_id' => 'integer',
        'photo' => 'string',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:50',
        'news_category_id' => 'required',
        'photo' => 'required',
        'content' => 'required'
    ];

    public function getContentSummaryAttribute($value) {
        $html = html_entity_decode(strip_tags($this->content));
        $summary = strlen($html) > 150 ? substr($html, 0, 150) . '...' : $html;

        return $summary;
    }

    public function latest($column = 'id') {
        return $this->orderBy($column, 'desc');
    }

    public function getDateAttribute($value) {
        $date = date('d F Y', strtotime($this->created_at));

        return $date;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class);
    }
}
