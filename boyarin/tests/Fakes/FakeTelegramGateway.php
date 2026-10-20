<?php

declare(strict_types=1);

namespace Boyarin\Tests\Fakes;

use Boyarin\Telegram\TelegramGateway;
use React\Promise\PromiseInterface;

final class FakeTelegramGateway implements TelegramGateway
{
    /** @var list<int> */
    public array $knownChecks = [];

    /** @var list<array{int, int}> */
    public array $deleted = [];

    /** @var list<array{int, int}> */
    public array $muted = [];

    public function __construct(private readonly bool $knownSpammer) {}

    public function isKnownSpammer(int $userId): PromiseInterface
    {
        $this->knownChecks[] = $userId;

        return \React\Promise\resolve($this->knownSpammer);
    }

    public function deleteMessage(int $chatId, int $messageId): PromiseInterface
    {
        $this->deleted[] = [$chatId, $messageId];

        return \React\Promise\resolve(null);
    }

    public function muteUser(int $chatId, int $userId): PromiseInterface
    {
        $this->muted[] = [$chatId, $userId];

        return \React\Promise\resolve(null);
    }
}
