<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'add:spam',
    description: 'Add a message to the local spam classifier dataset'
)]
final class AddSpamCommand extends AddClassifierExampleCommand
{
    protected $signature = 'add:spam
                            {message* : Message text to add to the spam dataset}';

    /**
     * Get the classifier dataset name.
     */
    protected function classifier(): string
    {
        return 'spam';
    }
}
