<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

use Boyarin\Config;
use InvalidArgumentException;
use React\EventLoop\Loop;
use React\Http\Browser;
use RuntimeException;
use Throwable;

final class WebhookRegistrar
{
    private readonly Browser $browser;

    public function __construct(private readonly Config $config)
    {
        $this->browser = (new Browser())->withTimeout(10.0);
    }

    /**
     * Register the configured endpoint as the Telegram bot webhook.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function register(bool $dropPendingUpdates = false): void
    {
        $this->validateUrl();

        $error = null;
        $url = "https://api.telegram.org/bot{$this->config->telegramToken}/setWebhook";
        $payload = [
            'url' => $this->config->webhookUrl,
            'secret_token' => $this->config->webhookSecret,
            'allowed_updates' => ['message'],
            'drop_pending_updates' => $dropPendingUpdates,
        ];

        $this->browser
            ->post($url, ['Content-Type' => 'application/json'], json_encode($payload, JSON_THROW_ON_ERROR))
            ->then(
                static function ($response) use (&$error): void {
                    $payload = json_decode((string) $response->getBody(), true);

                    if (($payload['ok'] ?? false) !== true) {
                        $error = new InvalidArgumentException(
                            $payload['description'] ?? 'Telegram rejected the webhook.',
                        );
                    }
                },
                static function (Throwable $exception) use (&$error): void {
                    $error = new RuntimeException(
                        'Unable to register webhook: '.$exception->getMessage(),
                        previous: $exception,
                    );
                },
            );

        Loop::run();

        throw_if($error !== null, $error);
    }

    /**
     * Validate the public webhook URL.
     *
     * @throws InvalidArgumentException
     */
    private function validateUrl(): void
    {
        throw_if(
            ! filter_var($this->config->webhookUrl, FILTER_VALIDATE_URL)
            || parse_url($this->config->webhookUrl, PHP_URL_SCHEME) !== 'https'
            || parse_url($this->config->webhookUrl, PHP_URL_PATH) !== $this->config->webhookPath,
            InvalidArgumentException::class,
            "WEBHOOK_URL must be a public HTTPS URL with path {$this->config->webhookPath}.",
        );
    }
}
