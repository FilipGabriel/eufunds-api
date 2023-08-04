<?php

namespace Modules\Checkout\Listeners;

use Swift_TransportException;
use Modules\Checkout\Mail\Invoice;
use Illuminate\Support\Facades\Mail;
use Modules\Checkout\Events\OrderPlaced;

class SendNewOrderEmails
{
    /**
     * Handle the event.
     *
     * @param \Modules\Checkout\Events\OrderPlaced $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $mailTo = [$event->order->customer_email];

        if($event->type == 'acquisition') {
            array_push($mailTo, $event->order->customer->manager_email);
        }

        try {
            Mail::to($mailTo)
                ->send(new Invoice($event->order));
        } catch (Swift_TransportException $e) {
            //
        }
    }
}
