<?php

namespace Smis\Events;

use Illuminate\Queue\SerializesModels;

class NewError
{
    use SerializesModels;

    /**
     * The error message.
     */
    public $error;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($error)
    {
        $this->error = $error;
    }
}
