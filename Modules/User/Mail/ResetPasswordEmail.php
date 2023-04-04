<?php

namespace Modules\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Modules\Media\Entities\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user entity.
     *
     * @var \Modules\User\Entities\User
     */
    public $user;

    /**
     * Reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new instance.
     *
     * @param \Modules\User\Entities\User $user
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('user::mail.reset_your_account_password'))
            ->view("emails.{$this->getViewName()}", [
                'logo' => File::findOrNew(setting('appfront_mail_logo'))->path,
                'url' => $this->resetUrl()
            ]);
    }


    /**
     * Get the reset URL for the given user.
     *
     * @return string
     */
    protected function resetUrl(): string
    {
        $origin = 'https://eufunds.ro';

        return URL::to($origin . "/reset-password/{$this->token}?email={$this->user->getEmailForPasswordReset()}");
    }

    private function getViewName()
    {
        return 'reset_password';
    }
}
