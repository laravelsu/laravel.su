<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

use Boyarin\Spam\SpamChecker;
use Boyarin\Spam\TrustedUsers;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;
use Throwable;

final readonly class UpdateProcessor implements UpdateHandler
{
    public function __construct(
        private TelegramGateway $telegram,
        private TrustedUsers $trustedUsers,
        private SpamChecker $detector,
        private LoggerInterface $logger,
    ) {}

    /**
     * Process an incoming Telegram update.
     *
     * @param array<string, mixed> $update
     */
    public function process(array $update): void
    {
        $message = data_get($update, 'message');
        if (! is_array($message)) {
            return;
        }

        $userId = (int) data_get($message, 'from.id', 0);
        $chatId = (int) data_get($message, 'chat.id', 0);
        $messageId = (int) data_get($message, 'message_id', 0);
        $text = (string) (data_get($message, 'text') ?? data_get($message, 'caption', ''));

        if ($userId === 0 || $chatId === 0 || $messageId === 0 || $text === '') {
            return;
        }

        if ($this->hasIgnoredEntity($message)) {
            return;
        }

        if ($this->trustedUsers->contains($userId)) {
            return;
        }

        $this->telegram->isKnownSpammer($userId)->then(
            function (bool $knownSpammer) use ($text, $userId, $chatId, $messageId): ?PromiseInterface {
                if (! $knownSpammer && ! $this->detector->isSpam($text)) {
                    return null;
                }

                $this->logger->info("Spam detected: user={$userId} chat={$chatId}");

                return \React\Promise\all([
                    $this->telegram->deleteMessage($chatId, $messageId),
                    $this->telegram->muteUser($chatId, $userId),
                ]);
            },
            fn (Throwable $error) => $this->logger->error($error->getMessage()),
        );
    }

    /**
     * Determine whether the message contains an entity that should bypass checks.
     *
     * @param array<string, mixed> $message
     */
    private function hasIgnoredEntity(array $message): bool
    {
        return collect(data_get($message, 'entities', []))
            ->contains(fn (mixed $entity): bool => in_array(
                data_get($entity, 'type'),
                ['pre'],
                true,
            ));
    }
}
