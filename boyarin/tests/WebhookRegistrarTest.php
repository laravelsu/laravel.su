<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Config;
use Boyarin\Telegram\WebhookRegistrar;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WebhookRegistrarTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidUrls(): iterable
    {
        yield 'empty URL' => [''];
        yield 'HTTP URL' => ['http://example.com/webhook/telegram'];
        yield 'wrong path' => ['https://example.com/wrong'];
    }

    #[Test]
    #[DataProvider('invalidUrls')]
    public function invalidWebhookUrlIsRejected(string $url): void
    {
        $registrar = new WebhookRegistrar($this->config($url));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'WEBHOOK_URL must be a public HTTPS URL with path /webhook/telegram.',
        );

        $registrar->register();
    }

    private function config(string $webhookUrl): Config
    {
        return new Config(
            host: '127.0.0.1',
            port: 8080,
            webhookPath: '/webhook/telegram',
            webhookUrl: $webhookUrl,
            webhookSecret: 'secret',
            telegramToken: 'token',
            muteSeconds: 86400,
            casCacheTtl: 18000,
            classifiersPath: '/unused',
            trustedUsersPath: '/unused',
            stopWordsPath: '/unused',
        );
    }
}
