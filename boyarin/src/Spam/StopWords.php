<?php

declare(strict_types=1);

namespace Boyarin\Spam;

use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

final class StopWords
{
    /** @var list<string> */
    private array $words = [];

    public function __construct(private readonly string $path) {}

    /**
     * Validate and load the configured stop words.
     *
     * @throws RuntimeException
     */
    public function validate(): void
    {
        $contents = @file_get_contents($this->path);
        throw_if(
            $contents === false,
            RuntimeException::class,
            "Unable to read stop words file: {$this->path}",
        );

        try {
            $payload = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new RuntimeException(
                "Invalid JSON in stop words file: {$this->path}",
                previous: $error,
            );
        }

        $words = data_get($payload, 'words');
        throw_unless(
            is_array($words),
            RuntimeException::class,
            'Stop words file must contain a "words" array.',
        );

        $this->words = collect($words)
            ->map(function (mixed $word, int $index): string {
                throw_unless(
                    is_string($word) && filled($word),
                    RuntimeException::class,
                    "Stop word at words.{$index} must be a non-empty string.",
                );

                return $word;
            })
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Determine whether any configured stop word occurs in the message.
     */
    public function arePresentIn(string $message): bool
    {
        if ($this->words === []) {
            $this->validate();
        }

        return Str::of($message)->contains($this->words, true);
    }
}
