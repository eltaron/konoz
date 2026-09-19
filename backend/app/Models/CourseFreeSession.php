<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFreeSession extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['course_id', 'title_ar', 'title_en', 'video_url', 'description_ar', 'description_en'];

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function getDescriptionAttribute()
    {
        return $this->localizedField('description_ar', 'description_en');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
