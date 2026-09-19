<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = ['student_id', 'exam_id', 'answers', 'score', 'correct_count', 'total_questions', 'submitted_at'];

    protected $casts = ['answers' => 'array', 'submitted_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
