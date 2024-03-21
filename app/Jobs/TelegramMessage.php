<?php

namespace App\Jobs;

use App\Services\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $text;
    public $from;
    public $chatId;
    public $messageId;

    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $message, public TelegramBot $telegramBot)
    {
        $this->text = $this->message->only(['text', 'caption'])->first();
        $this->messageId = $this->message->get('message_id');
        $this->chatId = $this->message->get('chat.id');
        $this->from = $this->message->get('from.id');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // If the message is a reply to another message, it's likely not spam.
        // Let's not disrupt the conversation and ignore it.
        if ($this->message->has('reply_to_message')) {
            return;
        }

        if ($this->telegramBot->isSpam($this->text)) {
            $this->blocked();
        }
    }

    /**
     * Block the message and mute the sender in the group.
     */
    public function blocked(): void
    {
        // Delete the spam message from the group chat.
        $this->telegramBot->deleteMessage($this->chatId, $this->messageId);

        // Mute the sender of the spam message in the group chat.
        $this->telegramBot->muteUserInGroup($this->chatId, $this->from);
    }
}
