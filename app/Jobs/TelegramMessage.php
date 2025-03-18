<?php

namespace App\Jobs;

use App\Models\AntiSpamRecord;
use App\Services\Telegram\CaptchaCallback;
use App\Services\Telegram\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $text;
    public $from;
    public $chatId;
    public $messageId;

    public $firstName;

    public $newChatMember;

    public $locale;

    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $message, public ?CaptchaCallback $captcha)
    {
        $this->text = $this->message->only(['text', 'caption'])->first();
        $this->messageId = $this->message->get('message_id');
        $this->firstName = $this->message->dot()->get('from.first_name');
        $this->chatId = $this->message->dot()->get('chat.id');
        $this->from = $this->message->dot()->get('from.id');
        $this->newChatMember = (bool) $this->message->get('new_chat_member');

        Log::channel('telegram')->info('CHAT MEMBER CONSTRUCTOR:'.$this->newChatMember);

        $chatConfig = collect(config('telegram.chats'))
            ->where('id', $this->chatId)
            ->first();

        $this->locale = $chatConfig ? $chatConfig['locale'] : config('telegram.default_locale');

        Log::channel('telegram')->info("TG Message Locale: $this->locale| TG Message chat:".json_encode($chatConfig));
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramBot $telegramBot): void
    {
        // Ban new user without duration and send captcha button
        /*
        if ($this->newChatMember) {
            Log::channel('telegram')->info('NEW CHAT MEMBER IF');
            App::setLocale($this->locale);
            $telegramBot->banUserInGroup($this->chatId, $this->from);
            $telegramBot->sendWelcomeButton($this->chatId, $this->from, $this->firstName);

            return;
        }
        */

        // Unmute user after click button
        if ($this->captcha?->checkId && ($this->captcha->checkId === $this->captcha->from)) {

            $telegramBot->unmuteUserInGroup($this->captcha->chatId, $this->captcha->from);

            $messageId = Cache::get("tg_message_{$this->captcha->chatId}_{$this->captcha->from}");

            if ($messageId) {
                $telegramBot->deleteMessage($this->captcha->chatId, $messageId);
                Cache::forget("tg_message_{$this->captcha->chatId}_{$this->captcha->from}");
            }

            return;
        }

        // Return when captcha clicked not muted users.
        if ($this->captcha?->checkId && ($this->captcha->checkId !== $this->captcha->from)) {
            return;
        }

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
