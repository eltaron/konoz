<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentActivity extends Model
{
    protected $fillable = ['student_id', 'date', 'activity_type', 'juz_number', 'notes', 'duration_minutes'];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
