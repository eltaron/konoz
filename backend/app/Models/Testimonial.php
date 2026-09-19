<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['student_name', 'content_ar', 'content_en', 'rating', 'image', 'is_published'];

    protected $casts = ['is_published' => 'boolean', 'rating' => 'integer'];

    public function getContentAttribute()
    {
        return $this->localizedField('content_ar', 'content_en');
    }
}
