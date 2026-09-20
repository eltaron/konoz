<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $resetUrl;
    public string $siteName;
    public string $siteUrl;

    public function __construct(string $userName, string $token)
    {
        $this->userName = $userName;
        $this->resetUrl = url(route('password.reset', $token, false));
        $this->siteName = 'منصة كُنوز التعليمية';
        $this->siteUrl = url('/');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إعادة تعيين كلمة المرور 🔐',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'userName' => $this->userName,
                'ctaUrl' => $this->resetUrl,
                'ctaLabel' => 'إعادة تعيين كلمة المرور',
                'siteName' => $this->siteName,
                'siteUrl' => $this->siteUrl,
                'subject' => 'إعادة تعيين كلمة المرور 🔐',
            ],
        );
    }
}