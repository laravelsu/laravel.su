<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Boyarin\Telegram\WebhookRegistrar;
use Illuminate\Console\Command;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'webhook:register',
    description: 'Register the Boyarin endpoint as the Telegram bot webhook'
)]
final class RegisterWebhookCommand extends Command
{
    protected $signature = 'webhook:register {--drop-pending-updates : Discard updates waiting at Telegram}';

    public function __construct(private readonly WebhookRegistrar $registrar)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->registrar->register((bool) $this->option('drop-pending-updates'));
        } catch (InvalidArgumentException $error) {
            $this->components->error($error->getMessage());

            return self::INVALID;
        } catch (RuntimeException $error) {
            $this->components->error($error->getMessage());

            return self::FAILURE;
        }

        $this->components->info('Telegram webhook registered successfully.');

        return self::SUCCESS;
    }
}
