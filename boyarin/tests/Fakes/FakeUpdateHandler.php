<?php

declare(strict_types=1);

namespace Boyarin\Tests\Fakes;

use Boyarin\Telegram\UpdateHandler;

final class FakeUpdateHandler implements UpdateHandler
{
    /** @var list<array<string, mixed>> */
    public array $updates = [];

    /**
     * @param  array<string, mixed>  $update
     */
    public function process(array $update): void
    {
        $this->updates[] = $update;
    }
}
