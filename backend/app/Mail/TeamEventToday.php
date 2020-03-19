<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamEventToday extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $member)
    {
        $this->event = $event;
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reminder for Today's Event")
                    ->replyTo(env('MAIL_FROM_ADDRESS'))
                    ->view('email.team.eventToday');
    }
}
