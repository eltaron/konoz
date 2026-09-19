<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['title_ar', 'title_en', 'subtitle_ar', 'subtitle_en', 'image', 'link', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function getSubtitleAttribute()
    {
        return $this->localizedField('subtitle_ar', 'subtitle_en');
    }
}
