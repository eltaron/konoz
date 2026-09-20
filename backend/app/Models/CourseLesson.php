<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['course_id', 'name_ar', 'name_en', 'order', 'link'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        $link = $this->link;
        if (!$link) {
            return null;
        }
        $patterns = [
            '/(?:youtube\.com|youtube-nocookie\.com)\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com|youtube-nocookie\.com)\/embed\/([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com|youtube-nocookie\.com)\/shorts\/([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com|youtube-nocookie\.com)\/live\/([a-zA-Z0-9_-]{11})/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $link, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    public function getYoutubeEmbedAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? 'https://www.youtube-nocookie.com/embed/' . $id . '?rel=0&modestbranding=1' : null;
    }
}