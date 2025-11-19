<?php

namespace App\Notifications;

use App\Models\Feature;
use App\Models\User;
use App\Notifications\Channels\SiteChannel;
use App\Notifications\Channels\SiteMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeatureNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Feature $feature)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            SiteChannel::class,
        ];
    }

    /**
     * Get the app representation of the notification.
     *
     * @param mixed $user
     *
     * @return SiteMessage
     */
    public function toSite(User $user)
    {
        $siteMessageBuilder =  (new SiteMessage)
            ->title("Предложенная функция \"{$this->feature->title}\" сменила статус на: {$this->feature->status->text()}");

        if ($this->feature->isImplemented() || $this->feature->isPublished()) {
            $siteMessageBuilder->action(route('features.index'), 'Посмотреть');
        }
        return $siteMessageBuilder;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
