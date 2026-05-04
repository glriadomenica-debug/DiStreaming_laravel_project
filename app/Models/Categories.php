<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function movies() {
        return $this->hasMany(Movies::class, 'category_id');
    }
}
