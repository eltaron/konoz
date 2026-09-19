<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['exam_id', 'question_ar', 'question_en', 'options', 'correct_answer', 'order'];

    protected $casts = ['options' => 'array'];

    public function getQuestionAttribute()
    {
        return $this->localizedField('question_ar', 'question_en');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
