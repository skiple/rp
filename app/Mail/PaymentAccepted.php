<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Transaction;
use App\User;

class PaymentAccepted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance (App/User)
     *
     * @var user
     */
    public $user;
    public $transaction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Transaction $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@rentuff.id', 'Rentuff Admin')
                    ->subject('Thank you for paying!')
                    ->view('emails.payment_accepted');
    }
}
