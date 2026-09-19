<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['student_id', 'course_id', 'title_ar', 'title_en', 'issued_at', 'status', 'image', 'is_published', 'verification_code'];

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            if (empty($certificate->verification_code)) {
                $certificate->verification_code = static::generateVerificationCode();
            }
        });
    }

    public static function generateVerificationCode(): string
    {
        do {
            $code = 'KNZ-' . strtoupper(Str::random(8));
        } while (static::where('verification_code', $code)->exists());
        return $code;
    }

    public function getTitleAttribute()
    {
        return $this->localizedField('title_ar', 'title_en');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        if (Str::startsWith($this->image, 'storage/')) {
            return asset($this->image);
        }
        if (Str::startsWith($this->image, 'certificates/')) {
            return asset('storage/' . $this->image);
        }
        return asset($this->image);
    }

    public function getVerificationUrlAttribute(): ?string
    {
        if (empty($this->verification_code)) {
            return null;
        }
        return route('certificate.verify', ['code' => $this->verification_code]);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
