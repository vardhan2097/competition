<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Organization;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inviteUrl;
    public $orgName;
    public $designation;

    /**
     * Create a new message instance.
     */
    public function __construct($inviteUrl, $orgName, $designation)
    {
        $this->inviteUrl = $inviteUrl;
        $this->orgName = $orgName;
        $this->designation = $designation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        $orgName = $this->orgName;
        $designation = $this->designation;
        $url = $this->inviteUrl;
        return $this->subject('You are invited to join Organization')->view('emails.userInvitation', compact('orgName', 'designation', 'url'));
    }
}
