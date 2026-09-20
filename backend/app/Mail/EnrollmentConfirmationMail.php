<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnrollmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $courseName;
    public ?string $courseLevel;
    public ?string $coursePrice;
    public string $requestedAt;
    public string $siteName;
    public string $siteUrl;

    public function __construct(string $userName, string $courseName, ?string $courseLevel = null, ?string $coursePrice = null)
    {
        $this->userName = $userName;
        $this->courseName = $courseName;
        $this->courseLevel = $courseLevel;
        $this->coursePrice = $coursePrice;
        $this->requestedAt = now()->translatedFormat('l d F Y');
        $this->siteName = 'منصة كُنوز التعليمية';
        $this->siteUrl = url('/');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم استلام طلب التسجيل في الدورة ✅',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enrollment',
            with: [
                'userName' => $this->userName,
                'courseName' => $this->courseName,
                'courseLevel' => $this->courseLevel,
                'coursePrice' => $this->coursePrice,
                'requestedAt' => $this->requestedAt,
                'siteName' => $this->siteName,
                'siteUrl' => $this->siteUrl,
                'subject' => 'تم استلام طلب التسجيل في الدورة ✅',
            ],
        );
    }
}