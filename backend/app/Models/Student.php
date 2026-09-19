<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['user_id', 'name_ar', 'name_en', 'gender', 'email', 'phone', 'age', 'level', 'status', 'joined_at'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('enrolled_at');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function enrollmentRequests()
    {
        return $this->hasMany(EnrollmentRequest::class);
    }

    public function pendingRequests()
    {
        return $this->hasMany(EnrollmentRequest::class)->where('status', 'pending');
    }

    public function juzProgress()
    {
        return $this->hasMany(JuzProgress::class)->orderBy('juz_number');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function activities()
    {
        return $this->hasMany(StudentActivity::class);
    }
}
