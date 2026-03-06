<?php

namespace App\Notifications;

use App\Models\Idea;
use App\Models\User;
use App\Notifications\Channels\SiteChannel;
use App\Notifications\Channels\SiteMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IdeaNotification extends Notification
{
    use Queueable;

    public function __construct(private Idea $idea) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            SiteChannel::class,
        ];
    }

    public function toSite(User $user): SiteMessage
    {
        $siteMessageBuilder = (new SiteMessage)
            ->title("Идея \"{$this->idea->title}\" сменила статус на: {$this->idea->status->text()}");

        if ($this->idea->isImplemented() || $this->idea->isPublished()) {
            $siteMessageBuilder->action(route('ideas.index'), 'Посмотреть');
        }

        return $siteMessageBuilder;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
