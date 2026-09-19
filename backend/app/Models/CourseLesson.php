<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['course_id', 'name_ar', 'name_en', 'order', 'link'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
