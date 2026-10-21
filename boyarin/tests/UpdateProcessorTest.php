<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Spam\TrustedUsers;
use Boyarin\Telegram\UpdateProcessor;
use Boyarin\Tests\Fakes\FakeLogger;
use Boyarin\Tests\Fakes\FakeSpamChecker;
use Boyarin\Tests\Fakes\FakeTelegramGateway;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UpdateProcessorTest extends TestCase
{
    private string $trustedUsersPath;

    protected function setUp(): void
    {
        $this->trustedUsersPath = sys_get_temp_dir().'/boyarin-processor-'.bin2hex(random_bytes(6)).'.json';
        file_put_contents($this->trustedUsersPath, '[42]');
    }

    protected function tearDown(): void
    {
        @unlink($this->trustedUsersPath);
    }

    #[Test]
    public function trustedUserIsSkipped(): void
    {
        [$processor, $telegram, $checker] = $this->processor();

        $processor->process($this->update(userId: 42));

        self::assertSame([], $telegram->knownChecks);
        self::assertSame([], $checker->messages);
    }

    #[Test]
    public function preformattedCodeIsSkipped(): void
    {
        [$processor, $telegram] = $this->processor();

        $processor->process($this->update(extra: ['entities' => [['type' => 'pre']]]));

        self::assertSame([], $telegram->knownChecks);
    }

    #[Test]
    public function cleanReplyIsCheckedWithoutClassifyingQuotedMessage(): void
    {
        [$processor, $telegram, $checker] = $this->processor();

        $processor->process($this->update(extra: [
            'reply_to_message' => ['message_id' => 1, 'text' => 'quoted parent message'],
        ]));

        self::assertSame([99], $telegram->knownChecks);
        self::assertSame(['обычное сообщение'], $checker->messages);
        self::assertSame([], $telegram->deleted);
        self::assertSame([], $telegram->muted);
    }

    #[Test]
    public function locallyDetectedSpamReplyIsDeletedAndMuted(): void
    {
        [$processor, $telegram, $checker] = $this->processor(localSpam: true);

        $processor->process($this->update(extra: [
            'text' => 'Ваш ВПН самый лучший!! Просто чудо',
            'reply_to_message' => ['message_id' => 1, 'text' => 'quoted parent message'],
        ]));

        self::assertSame(['Ваш ВПН самый лучший!! Просто чудо'], $checker->messages);
        self::assertSame([[-100, 10]], $telegram->deleted);
        self::assertSame([[-100, 99]], $telegram->muted);
    }

    #[Test]
    public function casSpammerReplyIsDeletedAndMutedWithoutLocalClassification(): void
    {
        [$processor, $telegram, $checker] = $this->processor(knownSpammer: true);

        $processor->process($this->update(extra: [
            'reply_to_message' => ['message_id' => 1, 'text' => 'quoted parent message'],
        ]));

        self::assertSame([], $checker->messages);
        self::assertSame([[-100, 10]], $telegram->deleted);
        self::assertSame([[-100, 99]], $telegram->muted);
    }

    #[Test]
    public function urlMessageIsStillChecked(): void
    {
        [$processor, $telegram, $checker] = $this->processor();

        $processor->process($this->update(extra: ['entities' => [['type' => 'url']]]));

        self::assertSame([99], $telegram->knownChecks);
        self::assertSame(['обычное сообщение'], $checker->messages);
    }

    #[Test]
    public function cleanMessageCausesNoModerationActions(): void
    {
        [$processor, $telegram, $checker] = $this->processor();

        $processor->process($this->update());

        self::assertSame(['обычное сообщение'], $checker->messages);
        self::assertSame([], $telegram->deleted);
        self::assertSame([], $telegram->muted);
    }

    #[Test]
    public function casSpammerIsDeletedAndMutedWithoutLocalClassification(): void
    {
        [$processor, $telegram, $checker] = $this->processor(knownSpammer: true);

        $processor->process($this->update());

        self::assertSame([], $checker->messages);
        self::assertSame([[-100, 10]], $telegram->deleted);
        self::assertSame([[-100, 99]], $telegram->muted);
    }

    #[Test]
    public function locallyDetectedSpamIsDeletedAndMuted(): void
    {
        [$processor, $telegram, $checker, $logger] = $this->processor(localSpam: true);

        $processor->process($this->update());

        self::assertSame(['обычное сообщение'], $checker->messages);
        self::assertSame([[-100, 10]], $telegram->deleted);
        self::assertSame([[-100, 99]], $telegram->muted);
        self::assertSame(['Spam detected: user=99 chat=-100'], $logger->info);
    }

    /**
     * @return array{UpdateProcessor, FakeTelegramGateway, FakeSpamChecker, FakeLogger}
     */
    private function processor(bool $knownSpammer = false, bool $localSpam = false): array
    {
        $telegram = new FakeTelegramGateway($knownSpammer);
        $checker = new FakeSpamChecker($localSpam);
        $logger = new FakeLogger();

        return [
            new UpdateProcessor(
                $telegram,
                new TrustedUsers($this->trustedUsersPath, $logger),
                $checker,
                $logger,
            ),
            $telegram,
            $checker,
            $logger,
        ];
    }

    /**
     * @param array<string, mixed> $extra
     *
     * @return array<string, mixed>
     */
    private function update(int $userId = 99, array $extra = []): array
    {
        return [
            'message' => array_replace_recursive([
                'message_id' => 10,
                'text'       => 'обычное сообщение',
                'from'       => ['id' => $userId],
                'chat'       => ['id' => -100],
            ], $extra),
        ];
    }
}
