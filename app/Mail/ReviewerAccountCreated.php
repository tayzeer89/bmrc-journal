<?php

namespace App\Mail;

use App\Models\Reviewer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewerAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Reviewer $reviewer;

    public string $temporaryPassword;


    public function __construct(
        Reviewer $reviewer,
        string $temporaryPassword
    ) {
        $this->reviewer = $reviewer;

        $this->temporaryPassword =
            $temporaryPassword;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject:
                'BMRC Reviewer Account Login Information'
        );
    }


    public function content(): Content
    {
        return new Content(
            view:
                'emails.reviewers.account-created'
        );
    }


    public function attachments(): array
    {
        return [];
    }
}