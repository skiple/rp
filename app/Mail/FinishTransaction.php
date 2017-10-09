<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Activity;

class FinishTransaction extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance (App/User)
     *
     * @var user
     */
    public $user;

    /**
     * The activity instance (App/Activity)
     *
     * @var activity
     */
    public $activity;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Activity $activity)
    {
        $this->user = $user;
        $this->activity = $activity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@rentuff.id', 'Rentuff Admin')
                    ->subject('Thank you for participating!')
                    ->view('emails.transaction_finished');
    }
}
