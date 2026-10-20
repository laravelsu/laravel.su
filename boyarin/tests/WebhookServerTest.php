<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Config;
use Boyarin\Spam\StopWords;
use Boyarin\Spam\TrustedUsers;
use Boyarin\Telegram\WebhookServer;
use Boyarin\Tests\Fakes\FakeLogger;
use Boyarin\Tests\Fakes\FakeUpdateHandler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Loop;
use React\Http\Message\ServerRequest;

final class WebhookServerTest extends TestCase
{
    private array $paths = [];

    protected function tearDown(): void
    {
        foreach ($this->paths as $path) {
            @unlink($path);
        }
    }

    #[Test]
    public function healthEndpointIsAvailable(): void
    {
        [$server] = $this->server();

        $response = $server->handle(new ServerRequest('GET', '/health'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('ok', (string) $response->getBody());
    }

    #[Test]
    public function unknownRouteIsRejected(): void
    {
        [$server] = $this->server();

        self::assertSame(404, $server->handle(new ServerRequest('GET', '/unknown'))->getStatusCode());
    }

    #[Test]
    public function webhookRequiresTelegramSecret(): void
    {
        [$server] = $this->server();

        $response = $server->handle(new ServerRequest('POST', '/webhook/telegram', [], '{}'));

        self::assertSame(403, $response->getStatusCode());
    }

    #[Test]
    public function webhookRejectsInvalidJson(): void
    {
        [$server] = $this->server();
        $request = new ServerRequest(
            'POST',
            '/webhook/telegram',
            ['X-Telegram-Bot-Api-Secret-Token' => 'secret'],
            '{invalid',
        );

        self::assertSame(400, $server->handle($request)->getStatusCode());
    }

    #[Test]
    public function webhookRejectsJsonThatIsNotAnObject(): void
    {
        [$server] = $this->server();
        $request = new ServerRequest(
            'POST',
            '/webhook/telegram',
            ['X-Telegram-Bot-Api-Secret-Token' => 'secret'],
            'true',
        );

        self::assertSame(400, $server->handle($request)->getStatusCode());
    }

    #[Test]
    public function webhookAcceptsAndDispatchesUpdate(): void
    {
        [$server, $handler] = $this->server();
        $request = new ServerRequest(
            'POST',
            '/webhook/telegram',
            ['X-Telegram-Bot-Api-Secret-Token' => 'secret'],
            '{"message":{"message_id":1}}',
        );

        self::assertSame(202, $server->handle($request)->getStatusCode());
        Loop::run();
        self::assertSame([['message' => ['message_id' => 1]]], $handler->updates);
    }

    /**
     * @return array{WebhookServer, FakeUpdateHandler}
     */
    private function server(): array
    {
        $trustedUsersPath = $this->temporaryFile('[]');
        $stopWordsPath = $this->temporaryFile('{"words":["spam"]}');
        $handler = new FakeUpdateHandler();
        $config = new Config(
            host: '127.0.0.1',
            port: 8080,
            webhookPath: '/webhook/telegram',
            webhookUrl: 'https://example.com/webhook/telegram',
            webhookSecret: 'secret',
            telegramToken: 'token',
            muteSeconds: 86400,
            casCacheTtl: 18000,
            classifiersPath: '/unused',
            trustedUsersPath: $trustedUsersPath,
            stopWordsPath: $stopWordsPath,
        );

        return [
            new WebhookServer(
                $handler,
                $config,
                new TrustedUsers($trustedUsersPath, new FakeLogger()),
                new StopWords($stopWordsPath),
                new FakeLogger(),
            ),
            $handler,
        ];
    }

    private function temporaryFile(string $contents): string
    {
        $path = sys_get_temp_dir().'/boyarin-webhook-'.bin2hex(random_bytes(6)).'.json';
        file_put_contents($path, $contents);
        $this->paths[] = $path;

        return $path;
    }
}
