<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JuzProgress extends Model
{
    protected $table = 'student_juz_progress';

    protected $fillable = ['student_id', 'juz_number', 'status', 'notes', 'completed_at', 'started_at', 'target_review_at'];

    protected $casts = [
        'completed_at' => 'datetime',
        'started_at' => 'date',
        'target_review_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
