<?php

declare(strict_types=1);

namespace Boyarin\Spam;

use AssistedMindfulness\NaiveBayes\Classifier;
use Illuminate\Support\Str;

final class SpamDetector implements SpamChecker
{
    public const SPAM = 'spam';
    public const HAM = 'ham';

    private const PHP_SYMBOLS = [
        '$', '->', '::', '[', ']', '(', ')', '{', '}', '=>', '&&', '||', '!',
        '===', '!==', '==', '!=', '<', '>', '<=', '>=', '+', '-', '*', '/', '%', '**', '=',
    ];

    public function __construct(
        private readonly Classifier $classifier,
        private readonly StopWords $stopWords,
    ) {}

    /**
     * Determine whether any local anti-spam rule matches the message.
     */
    public function isSpam(string $message): bool
    {
        return $this->containsStopWords($message)
            || $this->hasTooManySpecialCharacters($message)
            || $this->classifier->most($message) === self::SPAM;
    }

    /**
     * Determine whether the message contains a configured stop word.
     */
    public function containsStopWords(string $message): bool
    {
        return $this->stopWords->arePresentIn($message);
    }

    /**
     * Determine whether special characters dominate the message.
     */
    public function hasTooManySpecialCharacters(string $message): bool
    {
        $withSpecial = Str::of($message)
            ->replace(self::PHP_SYMBOLS, ' ')
            ->replaceMatches('/[\p{P}]+/u', '')
            ->squish()
            ->length();

        $withoutSpecial = Str::of($message)
            ->replace(self::PHP_SYMBOLS, '')
            ->replaceMatches('/[^\p{L}\p{N}\p{Z}\s]/u', '')
            ->squish()
            ->length();

        if ($withoutSpecial < 1) {
            return true;
        }

        if ($withSpecial === $withoutSpecial) {
            return false;
        }

        $words = Str::of($message)->slug()->replace('-', ' ')->squish()->wordCount();

        return $words === 0 || round((($withSpecial - $withoutSpecial) / 2) / $words, 2) > 1;
    }
}
