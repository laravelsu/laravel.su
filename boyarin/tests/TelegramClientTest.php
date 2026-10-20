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
use React\Http\Message\ResponseException;
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

    #[Test]
    public function successfulDeletionReportsConfirmedSuccess(): void
    {
        $browser = $this->createStub(Browser::class);
        $browser->method('post')->willReturn(\React\Promise\resolve(new Response(200, [], '{"ok":true,"result":true}')));
        $logger = new FakeLogger();
        $client = new TelegramClient($this->config(), $logger, $browser);
        $actual = null;

        $client->deleteMessage(-100, 10)->then(static function ($value) use (&$actual): void {
            $actual = $value;
        });

        self::assertSame(['ok' => true, 'result' => true], $actual);
        self::assertSame(['Telegram deleteMessage succeeded.'], $logger->info);
        self::assertSame([], $logger->errors);
    }

    #[Test]
    public function rejectedDeletionReportsTelegramDescriptionWithoutSuccess(): void
    {
        $browser = $this->createStub(Browser::class);
        $error = new ResponseException(new Response(400, [], '{"ok":false,"description":"Bad Request: message to delete not found"}'));
        $browser->method('post')->willReturn(\React\Promise\reject($error));
        $logger = new FakeLogger();
        $client = new TelegramClient($this->config(), $logger, $browser);
        $actual = true;

        $client->deleteMessage(-100, 10)->then(static function ($value) use (&$actual): void {
            $actual = $value;
        });

        self::assertNull($actual);
        self::assertSame([], $logger->info);
        self::assertSame(['Telegram deleteMessage failed: Bad Request: message to delete not found'], $logger->errors);
    }

    #[Test]
    public function unsuccessfulJsonDeletionReportsFailure(): void
    {
        $browser = $this->createStub(Browser::class);
        $browser->method('post')->willReturn(\React\Promise\resolve(new Response(200, [], '{"ok":false,"description":"Bad Request: not enough rights"}')));
        $logger = new FakeLogger();
        $client = new TelegramClient($this->config(), $logger, $browser);
        $actual = true;

        $client->deleteMessage(-100, 10)->then(static function ($value) use (&$actual): void {
            $actual = $value;
        });

        self::assertNull($actual);
        self::assertSame([], $logger->info);
        self::assertSame(['Telegram deleteMessage failed: Bad Request: not enough rights'], $logger->errors);
    }

    #[Test]
    public function deletionExceptionDoesNotExposeTokenOrRequestUrl(): void
    {
        $browser = $this->createStub(Browser::class);
        $browser->method('post')->willReturn(\React\Promise\reject(new RuntimeException('Failed https://api.telegram.org/bottoken/deleteMessage credential token')));
        $logger = new FakeLogger();
        $client = new TelegramClient($this->config(), $logger, $browser);

        $client->deleteMessage(-100, 10);

        self::assertSame([], $logger->info);
        self::assertCount(1, $logger->errors);
        self::assertStringContainsString('Telegram deleteMessage failed:', $logger->errors[0]);
        self::assertStringNotContainsString('token', $logger->errors[0]);
        self::assertStringNotContainsString('https://', $logger->errors[0]);
        self::assertStringNotContainsString('api.telegram.org', $logger->errors[0]);
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
