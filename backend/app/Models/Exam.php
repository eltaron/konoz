<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['course_id', 'title_ar', 'title_en', 'date', 'total_students', 'avg_score', 'status'];

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
