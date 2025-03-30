<?php

namespace App\Jobs;

use App\Models\AntiSpamRecord;
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

    public $firstName;


    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $message)
    {
        $this->text = $this->message->only(['text', 'caption'])->first();
        $this->messageId = $this->message->get('message_id');
        $this->firstName = $this->message->dot()->get('from.first_name');
        $this->chatId = $this->message->dot()->get('chat.id');
        $this->from = $this->message->dot()->get('from.id');
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramBot $telegramBot): void
    {

        if (empty($this->from)) {
            return;
        }

        // If the message is a reply to another message, it's likely not spam.
        // Let's not disrupt the conversation and ignore it.
        if ($this->message->has('reply_to_message')) {
            return;
        }

        $existEntities = collect($this->message->get('entities'))
            ->whereIn('type', ['url', 'pre'])
            ->isNotEmpty();

        if ($existEntities) {
            return;
        }

        if (AntiSpamRecord::where('telegram_id', $this->from)->where('message_count', '>', 9)->exists()) {
            return;
        }

        if ($telegramBot->isSpam($this->text)) {
            // Delete the spam message from the group chat.
            $telegramBot->deleteMessage($this->chatId, $this->messageId);

            // Mute the sender of the spam message in the group chat.
            $telegramBot->muteUserInGroup($this->chatId, $this->from, 86_400);

            return;
        }

        AntiSpamRecord::where('telegram_id', $this->from)->firstOrCreate([
            'telegram_id' => $this->from,
        ])->increment('message_count');
    }
}
