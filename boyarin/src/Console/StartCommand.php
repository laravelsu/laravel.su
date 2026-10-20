<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Boyarin\Telegram\WebhookServer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(
    name: 'start',
    description: 'Validate configuration, register the webhook and start the server'
)]
final class StartCommand extends Command
{
    protected $signature = 'start {--skip-webhook : Do not register the Telegram webhook}';

    public function __construct(private readonly WebhookServer $server)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Preparing Boyarin.');

        try {
            $this->server->validateResources();
        } catch (Throwable $error) {
            $this->components->error($error->getMessage());

            return self::FAILURE;
        }

        if (! $this->option('skip-webhook')) {
            $webhookResult = $this->call('webhook:register');

            if ($webhookResult === self::INVALID) {
                $this->components->error('Webhook configuration was rejected.');

                return self::FAILURE;
            }

            if ($webhookResult !== self::SUCCESS) {
                $this->components->warn(
                    'Webhook registration failed; the server will still start and retry on the next restart.',
                );
            }
        }

        try {
            $this->server->run();
        } catch (Throwable $error) {
            $this->components->error($error->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
