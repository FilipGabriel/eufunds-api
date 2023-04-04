<?php

namespace Modules\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Modules\Media\Entities\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationCodeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user entity.
     *
     * @var \Modules\User\Entities\User
     */
    public $user;

    /**
     * Create a new instance.
     *
     * @param \Modules\User\Entities\User $user
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('user::mail.confirm_your_account'))
            ->view("emails.{$this->getViewName()}", [
                'logo' => File::findOrNew(setting('appfront_mail_logo'))->path,
                'url' => $this->verificationUrl()
            ]);
    }


    /**
     * Get the verification URL for the given user.
     *
     * @return string
     */
    protected function verificationUrl(): string
    {
        $url = URL::signedRoute(
            'verification.verify',
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
            ],
        );
        $origin = 'https://eufunds.ro';

	    $url = str_replace(env('BACKEND_DOMAIN', 'api.eufunds.ro'), $origin, $url);
        return str_replace('/' . Config::get('fortify.prefix'), '', $url);
    }

    private function getViewName()
    {
        return 'account_activation';
    }
}
