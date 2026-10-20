<?php

declare(strict_types=1);

namespace Boyarin\Spam;

interface SpamChecker
{
    /**
     * Determine whether the message should be treated as spam.
     */
    public function isSpam(string $message): bool;
}
