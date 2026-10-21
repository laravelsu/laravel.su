<?php

declare(strict_types=1);

namespace Boyarin\Spam;

use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

final class StopWords
{
    /** @var list<string>|null */
    private ?array $words = null;

    /** @var list<string> */
    private array $patterns = [];

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

        $words = collect($words)
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

        $patterns = is_array($payload) && array_key_exists('patterns', $payload) ? $payload['patterns'] : [];
        throw_unless(
            is_array($patterns) && array_is_list($patterns),
            RuntimeException::class,
            'Stop words "patterns" must be a list of regular expressions.',
        );

        foreach ($patterns as $index => $pattern) {
            throw_unless(
                is_string($pattern) && filled($pattern) && @preg_match($pattern, '') !== false,
                RuntimeException::class,
                "Stop pattern at patterns.{$index} must be a valid non-empty regular expression.",
            );
        }

        $this->words = $words;
        $this->patterns = $patterns;
    }

    /**
     * Determine whether any configured literal or pattern matches the message.
     */
    public function arePresentIn(string $message): bool
    {
        if ($this->words === null) {
            $this->validate();
        }

        if (Str::of($message)->contains($this->words, true)) {
            return true;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $message) === 1) {
                return true;
            }
        }

        return false;
    }
}
