<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'image', 'excerpt','category_id', 'reading_time', 'views'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
