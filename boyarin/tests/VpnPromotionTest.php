<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Spam\ClassifierLoader;
use Boyarin\Spam\SpamDetector;
use Boyarin\Spam\StopWords;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class VpnPromotionTest extends TestCase
{
    private static ?SpamDetector $detector = null;

    #[Test]
    #[DataProvider('messages')]
    public function classifiesVpnMessagesWithProductionRules(string $message, bool $expected): void
    {
        self::$detector ??= new SpamDetector(
            (new ClassifierLoader(dirname(__DIR__).'/resources/classifiers'))->load(),
            new StopWords(dirname(__DIR__).'/resources/stop-words.json'),
        );

        self::assertSame($expected, self::$detector->isSpam($message));
    }

    public static function messages(): array
    {
        return [
            'reported reply' => ['Ваш ВПН самый лучший!! Просто чудо', true],
            'lowercase praise' => ['Ваш впн просто великолепен, спасибо!', true],
            'recommendation' => ['Лучшего VPN я не встречал, всем рекомендую!', true],
            'miracle review' => ['Подключил ваш впн, чудо как хорошо работает!', true],
            'speed stability price review' => ['Впн отличный, скорость высокая, очень стабильный. Все прекрасно работает и открывается. Радует цена.', true],
            'thanks review' => ['Сап! Спасибо огромное за впн, который работает, респект!', true],
            'affection review' => ['Люблю вас и ваш ВПН самые лучшие', true],
            'friends recommendation' => ['Спасибо, впн реально афигенный, буду советовать вас всем друзьям', true],
            'latin punctuation' => ['ВАШ VPN — САМЫЙ ЛУЧШИЙ!!!', true],
            'docker tunnel question' => ['Ваш VPN отлично работает с Docker? У меня Laravel не подключается к базе через туннель.', false],
            'test stand comparison' => ['Ваш ВПН лучший для доступа к тестовому стенду или лучше WireGuard?', false],
            'setup question' => ['Как настроить VPN на Ubuntu?', false],
            'connection question' => ['Без VPN не подключается к серверу, кто сталкивался?', false],
            'self hosted issue' => ['У меня свой VPN на VPS, после обновления пропал доступ.', false],
            'laravel resolution' => ['Спасибо, проблема была в VPN, теперь Laravel работает.', false],
            'choice question' => ['Какой VPN лучше для доступа к тестовому стенду?', false],
            'broken service' => ['Ваш VPN перестал работать после обновления', false],
            'negative review' => ['Ваш VPN не самый лучший, постоянно отключается.', false],
            'ironic thanks' => ['Спасибо за VPN, но он не работает.', false],
            'technical thanks' => ['Спасибо за VPN, теперь Laravel работает.', false],
            'wireguard setup' => ['Лучший VPN — когда свой, как настроить WireGuard?', false],
            'laravel thanks' => ['Спасибо за помощь с Laravel, всё работает!', false],
        ];
    }

    #[Test]
    public function literalRulesRemainCompatibleWithoutPatterns(): void
    {
        $this->withRules(['words' => ['пишите в лс']], function (StopWords $rules): void {
            self::assertTrue($rules->arePresentIn('За подробностями ПИШИТЕ В ЛС'));
            self::assertFalse($rules->arePresentIn('Как настроить VPN?'));
        });
    }

    #[Test]
    public function supportsPatternOnlyRules(): void
    {
        $this->withRules(['words' => [], 'patterns' => ['~\\bVPN\\b~iu']], function (StopWords $rules): void {
            self::assertTrue($rules->arePresentIn('vpn'));
            self::assertFalse($rules->arePresentIn('Laravel'));
        });
    }

    #[Test]
    #[DataProvider('invalidPatterns')]
    public function rejectsInvalidPatternConfiguration(mixed $patterns): void
    {
        $this->expectException(RuntimeException::class);
        $this->withRules(['words' => [], 'patterns' => $patterns], static fn (StopWords $rules) => $rules->validate());
    }

    public static function invalidPatterns(): array
    {
        return [
            'null' => [null],
            'string' => ['vpn'],
            'map' => [['vpn' => '~vpn~']],
            'empty pattern' => [['']],
            'non-string' => [[17]],
            'invalid regex' => [['~[~']],
        ];
    }

    private function withRules(array $payload, callable $assertion): void
    {
        $path = tempnam(sys_get_temp_dir(), 'boyarin-vpn-rules-');
        file_put_contents($path, json_encode($payload, JSON_THROW_ON_ERROR));

        try {
            $assertion(new StopWords($path));
        } finally {
            unlink($path);
        }
    }
}
