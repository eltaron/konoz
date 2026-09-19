<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id', 'session_id', 'status', 'quiz_score', 'quiz_total', 'quiz_answers'];

    protected $casts = ['quiz_answers' => 'array'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
