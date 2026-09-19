<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentReceipt extends Model
{
    protected $fillable = [
        'enrollment_request_id',
        'student_id',
        'course_id',
        'payment_method_id',
        'receipt_path',
        'amount',
        'note',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function enrollmentRequest()
    {
        return $this->belongsTo(EnrollmentRequest::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getReceiptUrlAttribute(): ?string
    {
        if (!$this->receipt_path) {
            return null;
        }
        if (str_starts_with($this->receipt_path, ['http://', 'https://'])) {
            return $this->receipt_path;
        }
        if (str_starts_with($this->receipt_path, 'storage/')) {
            return asset($this->receipt_path);
        }
        return asset('storage/' . $this->receipt_path);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض',
            default => $this->status,
        };
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }
}
