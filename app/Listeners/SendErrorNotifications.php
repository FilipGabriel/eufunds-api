<?php

namespace Smis\Listeners;

use Smis\Events\NewError;
use Modules\User\Entities\User;
use Modules\User\Entities\Setting;
use Illuminate\Support\Facades\Mail;
use Berkayk\OneSignal\OneSignalFacade;
use Modules\Notification\Services\Notification;
use Smis\Console\Commands\NotificationEmailCommand;

class SendErrorNotifications
{
    private $notification;

    /**
     * Create a new instance.
     *
     * @param \Modules\Notification\Services\Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Handle the event.
     *
     * @param \Smis\Events\NewError $event
     * @param \Modules\Notification\Services\Notification $notification
     * @return void
     */
    public function handle(NewError $event)
    {
        $userIds = Setting::filterByKeys([ 
            'log_notifications' 
        ], 'web');

        $subject = 'Eroare noua';
        $message = "<p>{$event->error}</p>";
        $link = route('admin.maintenance.logs');

        $this->notification->to($userIds)->push(
            null,
            'admin',
            $subject,
            $message,
            0,
            now(),
            $link
        );

        $userIds = Setting::filterByKeys([ 
            'log_notifications' 
        ], 'browser', true);

        if(! empty(strip_tags($message)) && ! empty($userIds) && config('app.onesignal')) {
            OneSignalFacade::setParam('headings', [locale() => $subject, 'en' => $subject])
                ->sendNotificationToExternalUser(strip_tags($message), $userIds);
        }

        $emails = User::whereIn('id', Setting::filterByKeys([ 
            'log_notifications' 
        ], 'email'))->get()->pluck('email')->toArray();

        if(! empty($emails)) {
            Mail::to($emails)->queue((new NotificationEmailCommand( $subject, $message, $link ))->onQueue('newsletter'));
        }
    }
}
