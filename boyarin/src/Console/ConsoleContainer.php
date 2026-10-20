<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Illuminate\Container\Container;

/**
 * Illuminate Console 12 expects this Laravel Application method while commands
 * are running. Torch's original standalone example targeted an older version.
 */
final class ConsoleContainer extends Container
{
    public function runningUnitTests(): bool
    {
        return false;
    }
}
