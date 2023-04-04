<?php

namespace Modules\User\Listeners;

use Swift_TransportException;
use Modules\User\Events\CustomerRegistered;

class SendActivationCodeEmail
{
    /**
     * Handle the event.
     *
     * @param \Modules\User\Events\CustomerRegistered $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        try {
            $event->user->sendEmailVerificationNotification();
        } catch (Swift_TransportException $e) {
            //
        }
    }
}
