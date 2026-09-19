<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    protected $fillable = ['student_id', 'type', 'title', 'body', 'icon', 'url', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeUnread($q)
    {
        return $q->whereNull('read_at');
    }
}
