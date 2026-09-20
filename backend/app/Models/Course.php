<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'name_ar',
        'name_en',
        'desc_ar',
        'desc_en',
        'icon',
        'image',
        'price',
        'is_free',
        'level',
        'audience',
        'students_count',
        'duration',
        'sessions_per_week',
        'instructor_ar',
        'instructor_en',
        'instructor_bio_ar',
        'instructor_bio_en',
        'is_active',
        'user_id'
    ];

    protected $casts = ['is_active' => 'boolean', 'is_free' => 'boolean'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class);
    }

    public function freeSessions()
    {
        return $this->hasMany(CourseFreeSession::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('enrolled_at');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function instructor()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    use \App\Traits\HasLocalizedFields;

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function getDescAttribute()
    {
        return $this->localizedField('desc_ar', 'desc_en');
    }

    public function getInstructorNameAttribute()
    {
        return $this->localizedField('instructor_ar', 'instructor_en');
    }

    public function getInstructorBioAttribute()
    {
        return $this->localizedField('instructor_bio_ar', 'instructor_bio_en');
    }
}
