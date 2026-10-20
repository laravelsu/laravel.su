<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'add:ham',
    description: 'Add a message to the local ham classifier dataset'
)]
final class AddHamCommand extends AddClassifierExampleCommand
{
    protected $signature = 'add:ham
                            {message* : Message text to add to the ham dataset}';

    /**
     * Get the classifier dataset name.
     */
    protected function classifier(): string
    {
        return 'ham';
    }
}
