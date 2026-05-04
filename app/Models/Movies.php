<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Movies extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'rating',
        'release_year',
        'thumbnail',
        'video_url'
    ];
    protected $casts = [
        'rating' => 'float',
        'release_year' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
