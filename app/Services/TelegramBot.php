<?php

namespace App\Services;

use AssistedMindfulness\NaiveBayes\Classifier;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramBot
{
    public const SPAM = 'spam';
    public const HAM = 'ham';

    private $token;

    /**
     * Construct a new TelegramBot instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = config('services.telegram-bot-api.token');
    }

    /**
     * Mute a user in a group chat.
     *
     * @param int $chatId
     * @param int $userId
     * @param int $muteDuration
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function muteUserInGroup($chatId, $userId, $muteDuration = 60): Response
    {
        $url = "https://api.telegram.org/bot{$this->token}/restrictChatMember";

        return Http::post($url, [
            'chat_id'                   => $chatId,
            'user_id'                   => $userId,
            'until_date'                => time() + $muteDuration,
            'can_send_messages'         => false,
            'can_send_media_messages'   => false,
            'can_send_other_messages'   => false,
            'can_add_web_page_previews' => false,
        ]);
    }

    /**
     * Delete a message from a chat.
     *
     * @param int $chatId
     * @param int $messageId
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function deleteMessage($chatId, $messageId): Response
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteMessage";

        return Http::post($url, [
            'chat_id'    => $chatId,
            'message_id' => $messageId,
        ]);
    }

    /**
     * Check if a message is spam.
     *
     * @param string $message
     *
     * @return bool
     */
    public function isSpam(string $message): bool
    {
        $classifier = new Classifier();

        $classifier
            /**
             * Spam
             */
            ->learn('Здрaвcтвyйте, прeдостaвляю yдалённyю зaнятoсть. 770$+ в нeдeлю Кoмy интepeсно, пишитe  "+"  в личные', static::SPAM)
            ->learn('Всeх привeтствую. Нyжны пaртнёры для удалённoгo сoтрудничeства. Пoдробнoсти в лс', static::SPAM)

            /**
             * Hamming
             */
            ->learn('а учусь я потому что хочу работу нормальную найти и чтоб дети жили нормально)', static::HAM)
            ->learn('у тебя переменная передается не так надо массив ->asyncParameters()', static::HAM)
            ->learn('MVC. Можно ещё там использовать сервис контейнеры, фасады, view-model', static::HAM)
            ->learn('Попробуем, спасибо 🙏', static::HAM)
            ->learn('https://laravel.com/docs/', static::HAM)
            ->learn('Да', static::HAM)
            ->learn('Получилось', static::HAM);

        TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->line('Сообщение было классифицировано как '.$classifier->most($message))
            ->line('')
            ->line('*📂 Текст сообщения*')
            ->escapedLine($message)
            ->send();

        return Str::of($message)->contains([
            'yдалённyю',
            'в нeдeлю',
            'интepeсно',
            'пaртнёры',
            'сoтрудничeств',
        ]);
    }
}
