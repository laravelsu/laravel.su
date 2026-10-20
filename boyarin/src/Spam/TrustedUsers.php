<?php

declare(strict_types=1);

namespace Boyarin\Spam;

use JsonException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final class TrustedUsers
{
    /** @var list<string> */
    private array $ids = [];

    public function __construct(
        private readonly string $path,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Create the trusted-user file when missing, then validate and load it.
     *
     * @throws RuntimeException
     */
    public function prepare(): void
    {
        if (! file_exists($this->path)) {
            throw_if(
                @file_put_contents($this->path, "[]\n") === false,
                RuntimeException::class,
                "Unable to create trusted users file: {$this->path}",
            );
        }

        $this->ids = $this->load();
    }

    /**
     * Determine whether the Telegram user is trusted.
     */
    public function contains(int $telegramId): bool
    {
        try {
            $this->ids = $this->load();
        } catch (RuntimeException $error) {
            $this->logger->error($error->getMessage());
        }

        return in_array((string) $telegramId, $this->ids, true);
    }

    /**
     * The file is deliberately read on every check so operators can edit the
     * allowlist without restarting the long-running ReactPHP process.
     *
     * @return list<string>
     */
    private function load(): array
    {
        $contents = @file_get_contents($this->path);
        throw_if(
            $contents === false,
            RuntimeException::class,
            "Unable to read trusted users file: {$this->path}",
        );

        try {
            $payload = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new RuntimeException(
                "Invalid JSON in trusted users file: {$this->path}",
                previous: $error,
            );
        }

        throw_unless(
            is_array($payload) && array_is_list($payload),
            RuntimeException::class,
            'Trusted users file must contain a JSON array.',
        );

        return collect($payload)
            ->map(function (mixed $user, int $index): string {
                throw_unless(
                    is_int($user),
                    RuntimeException::class,
                    "Trusted user at index {$index} must be an integer Telegram ID.",
                );

                return (string) $user;
            })
            ->unique()
            ->values()
            ->all();
    }
}
