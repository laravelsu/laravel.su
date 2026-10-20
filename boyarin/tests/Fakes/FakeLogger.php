<?php

declare(strict_types=1);

namespace Boyarin\Tests\Fakes;

use Psr\Log\AbstractLogger;
use Stringable;

final class FakeLogger extends AbstractLogger
{
    /** @var list<string> */
    public array $info = [];

    /** @var list<string> */
    public array $errors = [];

    /**
     * Record a log message for assertions.
     *
     * @param  mixed  $level
     * @param  array<string, mixed>  $context
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        match ($level) {
            'error' => $this->errors[] = (string) $message,
            'info' => $this->info[] = (string) $message,
            default => null,
        };
    }
}
