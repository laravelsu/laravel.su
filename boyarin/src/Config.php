<?php

declare(strict_types=1);

namespace Boyarin;

use InvalidArgumentException;

final readonly class Config
{
    public function __construct(
        public string $host,
        public int $port,
        public string $webhookPath,
        public string $webhookUrl,
        public string $webhookSecret,
        public string $telegramToken,
        public int $muteSeconds,
        public int $casCacheTtl,
        public string $classifiersPath,
        public string $trustedUsersPath,
        public string $stopWordsPath,
    ) {}

    /**
     * Build the service configuration from environment variables.
     *
     * @throws InvalidArgumentException
     */
    public static function fromEnvironment(string $basePath): self
    {
        $config = new self(
            host: (string) env('SERVER_HOST', '0.0.0.0'),
            port: (int) env('SERVER_PORT', 8080),
            webhookPath: '/'.ltrim((string) env('WEBHOOK_PATH', '/webhook/telegram'), '/'),
            webhookUrl: (string) env('WEBHOOK_URL'),
            webhookSecret: (string) env('WEBHOOK_SECRET'),
            telegramToken: (string) env('TELEGRAM_BOT_TOKEN'),
            muteSeconds: (int) env('TELEGRAM_MUTE_SECONDS', 86400),
            casCacheTtl: (int) env('CAS_CACHE_TTL', 18000),
            classifiersPath: (string) env('CLASSIFIERS_PATH', $basePath.'/resources/classifiers'),
            trustedUsersPath: (string) env('TRUSTED_USERS_PATH', $basePath.'/resources/trusted-users.json'),
            stopWordsPath: (string) env('STOP_WORDS_PATH', $basePath.'/resources/stop-words.json'),
        );

        throw_if(
            blank($config->telegramToken),
            InvalidArgumentException::class,
            'TELEGRAM_BOT_TOKEN is required.',
        );
        throw_if(
            blank($config->webhookSecret),
            InvalidArgumentException::class,
            'WEBHOOK_SECRET is required.',
        );

        return $config;
    }
}
