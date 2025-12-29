<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        $url = route('invitations.accept', ['token' => $this->invitation->token]);

        return $this->subject('You are invited to join '.$this->invitation->organization->name)
                    ->view('emails.agent_invitation')
                    ->with(['url' => $url, 'invitation' => $this->invitation]);
    }
}
