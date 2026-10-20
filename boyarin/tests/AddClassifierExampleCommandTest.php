<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Config;
use Boyarin\Console\AddClassifierExampleCommand;
use Boyarin\Console\AddHamCommand;
use Boyarin\Console\AddSpamCommand;
use Boyarin\Console\ConsoleContainer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class AddClassifierExampleCommandTest extends TestCase
{
    private string $classifiersPath;

    protected function setUp(): void
    {
        $this->classifiersPath = sys_get_temp_dir().'/boyarin-'.bin2hex(random_bytes(8));

        mkdir($this->classifiersPath);
        file_put_contents(
            $this->classifiersPath.'/spam.json',
            json_encode(['Existing message'], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR).PHP_EOL,
        );
        file_put_contents(
            $this->classifiersPath.'/ham.json',
            json_encode(['Existing ham'], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR).PHP_EOL,
        );
    }

    protected function tearDown(): void
    {
        @unlink($this->classifiersPath.'/spam.json');
        @unlink($this->classifiersPath.'/ham.json');
        @rmdir($this->classifiersPath);
    }

    #[Test]
    public function it_adds_a_message_from_multiple_arguments(): void
    {
        $command = $this->command();

        $exitCode = $command->execute(['message' => ['Реклама!', 'Выплаты']]);

        self::assertSame(0, $exitCode);
        self::assertSame(
            ['Existing message', 'Реклама! Выплаты'],
            json_decode(
                (string) file_get_contents($this->classifiersPath.'/spam.json'),
                true,
                flags: JSON_THROW_ON_ERROR,
            ),
        );
    }

    #[Test]
    public function it_does_not_add_a_duplicate(): void
    {
        $command = $this->command();

        $command->execute(['message' => ['Existing', 'message']]);

        self::assertSame(
            ['Existing message'],
            json_decode(
                (string) file_get_contents($this->classifiersPath.'/spam.json'),
                true,
                flags: JSON_THROW_ON_ERROR,
            ),
        );
    }

    #[Test]
    public function it_adds_a_ham_message_to_the_ham_dataset(): void
    {
        $command = $this->command(new AddHamCommand($this->config()));

        $exitCode = $command->execute(['message' => ['Обсуждаем', 'Laravel']]);

        self::assertSame(0, $exitCode);
        self::assertSame(
            ['Existing ham', 'Обсуждаем Laravel'],
            json_decode(
                (string) file_get_contents($this->classifiersPath.'/ham.json'),
                true,
                flags: JSON_THROW_ON_ERROR,
            ),
        );
        self::assertSame(
            ['Existing message'],
            json_decode(
                (string) file_get_contents($this->classifiersPath.'/spam.json'),
                true,
                flags: JSON_THROW_ON_ERROR,
            ),
        );
    }

    #[Test]
    public function it_preserves_the_existing_dataset_format(): void
    {
        file_put_contents(
            $this->classifiersPath.'/spam.json',
            "[\n    \"\\uD83D\\uDD35 Русский текст\"\n]\n",
        );

        $this->command()->execute(['message' => ['Новый', 'пример']]);

        self::assertSame(
            "[\n    \"\\uD83D\\uDD35 Русский текст\",\n    \"Новый пример\"\n]\n",
            file_get_contents($this->classifiersPath.'/spam.json'),
        );
    }

    /**
     * Create a command tester with its Illuminate container.
     */
    private function command(?AddClassifierExampleCommand $command = null): CommandTester
    {
        $command ??= new AddSpamCommand($this->config());
        $command->setLaravel(new ConsoleContainer());

        return new CommandTester($command);
    }

    /**
     * Create a configuration for the command.
     */
    private function config(): Config
    {
        return new Config(
            host: '127.0.0.1',
            port: 8080,
            webhookPath: '/webhook/telegram',
            webhookUrl: 'https://example.com/webhook/telegram',
            webhookSecret: 'secret',
            telegramToken: 'token',
            muteSeconds: 86400,
            casCacheTtl: 18000,
            classifiersPath: $this->classifiersPath,
            trustedUsersPath: $this->classifiersPath.'/trusted-users.json',
            stopWordsPath: $this->classifiersPath.'/stop-words.json',
        );
    }
}
