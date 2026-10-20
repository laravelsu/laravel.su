<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Config;
use Boyarin\Telegram\TelegramClient;
use Boyarin\Tests\Fakes\FakeLogger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use React\Http\Browser;
use React\Http\Message\Response;
use RuntimeException;

final class TelegramClientTest extends TestCase
{
    #[Test]
    #[DataProvider('casResponses')]
    public function onlyConfirmedCasBanIsSpam(string $body, bool $expected): void
    {
        $browser = $this->createStub(Browser::class);
        $browser->method('get')->willReturn(\React\Promise\resolve(new Response(200, [], $body)));
        $client = new TelegramClient($this->config(), new FakeLogger(), $browser);
        $actual = null;

        $client->isKnownSpammer(99)->then(static function (bool $value) use (&$actual): void {
            $actual = $value;
        });

        self::assertSame($expected, $actual);
    }

    public static function casResponses(): iterable
    {
        yield 'confirmed ban' => ['{"ok":true,"result":{"offenses":2,"time_added":1700000000}}', true];
        yield 'not banned' => ['{"ok":false,"description":"Record not found."}', false];
        yield 'API error' => ['{"ok":false,"error_code":401,"description":"Unauthorized"}', false];
        yield 'missing ban' => ['{"ok":true}', false];
        yield 'empty ban' => ['{"ok":true,"result":{}}', false];
        yield 'null ban' => ['{"ok":true,"result":null}', false];
        yield 'list instead of ban' => ['{"ok":true,"result":[1]}', false];
        yield 'scalar instead of ban' => ['{"ok":true,"result":"banned"}', false];
        yield 'non boolean status' => ['{"ok":"true","result":{"offenses":2}}', false];
        yield 'invalid JSON' => ['{invalid', false];
        yield 'scalar JSON' => ['false', false];
    }

    #[Test]
    public function unavailableCasDoesNotMarkUserAsSpammer(): void
    {
        $browser = $this->createStub(Browser::class);
        $browser->method('get')->willReturn(\React\Promise\reject(new RuntimeException('CAS unavailable')));
        $logger = new FakeLogger();
        $client = new TelegramClient($this->config(), $logger, $browser);
        $actual = null;

        $client->isKnownSpammer(99)->then(static function (bool $value) use (&$actual): void {
            $actual = $value;
        });

        self::assertFalse($actual);
        self::assertCount(1, $logger->errors);
    }

    private function config(): Config
    {
        return new Config(
            host: '127.0.0.1',
            port: 8080,
            webhookPath: '/webhook/telegram',
            webhookUrl: 'https://example.com/webhook/telegram',
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
