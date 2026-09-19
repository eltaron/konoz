<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentRequest extends Model
{
    protected $fillable = ['student_id', 'course_id', 'status', 'notes', 'approved_at'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function paymentReceipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    public function latestPaymentReceipt()
    {
        return $this->hasOne(PaymentReceipt::class)->latestOfMany();
    }
}
