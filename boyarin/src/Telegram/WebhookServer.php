<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

use Boyarin\Config;
use Boyarin\Spam\StopWords;
use Boyarin\Spam\TrustedUsers;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Throwable;

final class WebhookServer
{
    private bool $validated = false;

    public function __construct(
        private readonly UpdateHandler $processor,
        private readonly Config $config,
        private readonly TrustedUsers $trustedUsers,
        private readonly StopWords $stopWords,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Start accepting webhook requests.
     */
    public function run(): void
    {
        $this->validateResources();
        $server = new HttpServer($this->handle(...));

        $address = $this->config->host.':'.$this->config->port;
        $server->listen(new SocketServer($address));

        $this->logger->info(
            "Boyarin is listening on http://{$address}{$this->config->webhookPath}",
        );
    }

    /**
     * Validate resources required by the long-running process.
     */
    public function validateResources(): void
    {
        if ($this->validated) {
            return;
        }

        $this->trustedUsers->prepare();
        $this->stopWords->validate();
        $this->validated = true;
    }

    /**
     * Handle an HTTP request received by the ReactPHP server.
     */
    public function handle(ServerRequestInterface $request): Response
    {
        if ($request->getUri()->getPath() === '/health') {
            return new Response(200, ['Content-Type' => 'text/plain'], 'ok');
        }

        if (
            $request->getMethod() !== 'POST'
            || $request->getUri()->getPath() !== $this->config->webhookPath
        ) {
            return new Response(404, ['Content-Type' => 'application/json'], '{"ok":false}');
        }

        $secret = $request->getHeaderLine('X-Telegram-Bot-Api-Secret-Token');
        if (! hash_equals($this->config->webhookSecret, $secret)) {
            return new Response(403, ['Content-Type' => 'application/json'], '{"ok":false}');
        }

        try {
            $update = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return new Response(400, ['Content-Type' => 'application/json'], '{"ok":false}');
        }

        if (! is_array($update)) {
            return new Response(400, ['Content-Type' => 'application/json'], '{"ok":false}');
        }

        Loop::futureTick(fn () => $this->processor->process($update));

        return new Response(202, ['Content-Type' => 'application/json'], '{"ok":true}');
    }
}
