<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Boyarin\Telegram\WebhookServer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(
    name: 'serve',
    description: 'Start the ReactPHP webhook server'
)]
final class ServeCommand extends Command
{
    protected $signature = 'serve';

    public function __construct(
        private readonly WebhookServer $server,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->server->run();
        } catch (Throwable $error) {
            $this->components->error($error->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
