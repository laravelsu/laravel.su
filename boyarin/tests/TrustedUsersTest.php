<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Spam\TrustedUsers;
use Boyarin\Tests\Fakes\FakeLogger;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class TrustedUsersTest extends TestCase
{
    /** @var list<string> */
    private array $paths = [];

    protected function tearDown(): void
    {
        collect($this->paths)
            ->filter(static fn (string $path): bool => file_exists($path))
            ->each(static fn (string $path) => unlink($path));
    }

    #[Test]
    public function trustedUserIsFoundByTelegramId(): void
    {
        $path = $this->temporaryFile('[42]');
        $repository = new TrustedUsers($path, new FakeLogger());

        self::assertTrue($repository->contains(42));
        self::assertFalse($repository->contains(43));
    }

    #[Test]
    public function trustedUsersAreReloadedWithoutRestarting(): void
    {
        $path = $this->temporaryFile('[]');
        $repository = new TrustedUsers($path, new FakeLogger());

        self::assertFalse($repository->contains(42));
        file_put_contents($path, '[42]');
        self::assertTrue($repository->contains(42));
    }

    #[Test]
    public function missingFileIsCreatedOnPrepare(): void
    {
        $path = $this->temporaryPath();
        $repository = new TrustedUsers($path, new FakeLogger());

        $repository->prepare();

        self::assertSame("[]\n", file_get_contents($path));
    }

    #[Test]
    public function invalidJsonIsRejected(): void
    {
        $path = $this->temporaryFile('{invalid');

        $this->expectException(RuntimeException::class);
        (new TrustedUsers($path, new FakeLogger()))->prepare();
    }

    #[Test]
    public function objectFormatIsRejected(): void
    {
        $path = $this->temporaryFile('{"users":[]}');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Trusted users file must contain a JSON array.');

        (new TrustedUsers($path, new FakeLogger()))->prepare();
    }

    #[Test]
    public function lastValidListIsKeptWhileFileIsBeingEdited(): void
    {
        $path = $this->temporaryFile('[42]');
        $logger = new FakeLogger();
        $repository = new TrustedUsers($path, $logger);
        $repository->prepare();

        file_put_contents($path, '{incomplete');

        self::assertTrue($repository->contains(42));
        self::assertCount(1, $logger->errors);
    }

    private function temporaryFile(string $contents): string
    {
        $path = $this->temporaryPath();
        file_put_contents($path, $contents);

        return $path;
    }

    private function temporaryPath(): string
    {
        $path = sys_get_temp_dir().'/boyarin-trusted-users-'.bin2hex(random_bytes(6)).'.json';
        $this->paths[] = $path;

        return $path;
    }
}
