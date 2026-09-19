<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['slug', 'tag', 'title_ar', 'title_en', 'content_ar', 'content_en', 'image', 'read_time', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function getContentAttribute()
    {
        return $this->localizedField('content_ar', 'content_en');
    }
}
