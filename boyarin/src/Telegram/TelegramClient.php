<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

use Boyarin\Config;
use Psr\Log\LoggerInterface;
use React\Http\Browser;
use React\Http\Message\ResponseException;
use React\Promise\PromiseInterface;
use Throwable;

final class TelegramClient implements TelegramGateway
{
    private readonly Browser $browser;

    /** @var array<int, array{value: bool, expires_at: int}> */
    private array $casCache = [];

    public function __construct(
        private readonly Config $config,
        private readonly LoggerInterface $logger,
        ?Browser $browser = null,
    )
    {
        $this->browser = $browser ?? new Browser();
    }

    /**
     * Determine whether CAS identifies the Telegram user as a spammer.
     *
     * @return PromiseInterface<bool>
     */
    public function isKnownSpammer(int $userId): PromiseInterface
    {
        $cached = $this->casCache[$userId] ?? null;
        if ($cached !== null && $cached['expires_at'] > time()) {
            return \React\Promise\resolve($cached['value']);
        }

        return $this->browser
            ->get('https://api.cas.chat/check?user_id='.$userId)
            ->then(
                function ($response) use ($userId): bool {
                    $payload = json_decode((string) $response->getBody());
                    $isSpammer = $payload instanceof \stdClass
                        && ($payload->ok ?? null) === true
                        && ($payload->result ?? null) instanceof \stdClass
                        && get_object_vars($payload->result) !== [];
                    $this->casCache[$userId] = [
                        'value' => $isSpammer,
                        'expires_at' => time() + $this->config->casCacheTtl,
                    ];

                    return $isSpammer;
                },
                function (Throwable $error): bool {
                    $this->logger->error('CAS request failed: '.$error->getMessage());

                    return false;
                },
            );
    }

    /**
     * Delete a message from a Telegram chat.
     *
     * @return PromiseInterface<mixed>
     */
    public function deleteMessage(int $chatId, int $messageId): PromiseInterface
    {
        return $this->callBotApi('deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);
    }

    /**
     * Temporarily prevent a Telegram user from sending messages.
     *
     * @return PromiseInterface<mixed>
     */
    public function muteUser(int $chatId, int $userId): PromiseInterface
    {
        return $this->callBotApi('restrictChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'until_date' => time() + $this->config->muteSeconds,
            'permissions' => [
                'can_send_messages' => false,
                'can_send_audios' => false,
                'can_send_documents' => false,
                'can_send_photos' => false,
                'can_send_videos' => false,
                'can_send_video_notes' => false,
                'can_send_voice_notes' => false,
                'can_send_polls' => false,
                'can_send_other_messages' => false,
                'can_add_web_page_previews' => false,
                'can_change_info' => false,
                'can_invite_users' => false,
                'can_pin_messages' => false,
                'can_manage_topics' => false,
            ],
        ]);
    }

    /**
     * Call a Telegram Bot API method.
     *
     * @param  array<string, mixed>  $payload
     * @return PromiseInterface<mixed>
     */
    private function callBotApi(string $method, array $payload): PromiseInterface
    {
        $url = "https://api.telegram.org/bot{$this->config->telegramToken}/{$method}";

        return $this->browser
            ->post($url, ['Content-Type' => 'application/json'], json_encode($payload, JSON_THROW_ON_ERROR))
            ->then(
                function ($response) use ($method): ?array {
                    $result = json_decode((string) $response->getBody(), true);
                    if (is_array($result) && ($result['ok'] ?? null) === true) {
                        $this->logger->info("Telegram {$method} succeeded.");

                        return $result;
                    }

                    $description = is_array($result) ? ($result['description'] ?? null) : null;
                    $this->logBotApiFailure($method, is_string($description) ? $description : 'Invalid or unsuccessful API response.');

                    return null;
                },
                function (Throwable $error) use ($method): null {
                    $description = $error->getMessage();
                    if ($error instanceof ResponseException) {
                        $result = json_decode((string) $error->getResponse()->getBody(), true);
                        if (is_array($result) && is_string($result['description'] ?? null)) {
                            $description = $result['description'];
                        }
                    }
                    $this->logBotApiFailure($method, $description);

                    return null;
                },
            );
    }

    private function logBotApiFailure(string $method, string $description): void
    {
        $description = preg_replace('~https?://[^\s]+~i', '[redacted URL]', $description) ?? 'Request failed.';
        if ($this->config->telegramToken !== '') {
            $description = str_replace($this->config->telegramToken, '[redacted]', $description);
        }
        $this->logger->error("Telegram {$method} failed: ".$description);
    }
}
