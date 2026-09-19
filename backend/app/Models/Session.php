<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $table = 'course_sessions';

    protected $fillable = ['course_id', 'title_ar', 'title_en', 'date', 'time_from', 'time_to', 'status', 'quiz_data', 'stream_url'];

    protected $casts = ['quiz_data' => 'array'];

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
