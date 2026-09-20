<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $siteName;
    public string $siteUrl;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
        $this->siteName = 'منصة كُنوز التعليمية';
        $this->siteUrl = url('/');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'مرحباً بك في منصة كُنوز التعليمية 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'userName' => $this->userName,
                'siteName' => $this->siteName,
                'siteUrl' => $this->siteUrl,
                'subject' => 'مرحباً بك في منصة كُنوز التعليمية 🎉',
            ],
        );
    }
}