<?php

declare(strict_types=1);

namespace Boyarin\Tests\Fakes;

use Boyarin\Spam\SpamChecker;

final class FakeSpamChecker implements SpamChecker
{
    /** @var list<string> */
    public array $messages = [];

    public function __construct(private readonly bool $spam) {}

    public function isSpam(string $message): bool
    {
        $this->messages[] = $message;

        return $this->spam;
    }
}
