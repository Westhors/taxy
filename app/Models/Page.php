<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'content_en',
        'content_ar',
    ];

    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getContentAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }
}
