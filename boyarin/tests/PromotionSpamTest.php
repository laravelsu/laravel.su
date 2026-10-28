<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use Boyarin\Spam\ClassifierLoader;
use Boyarin\Spam\SpamDetector;
use Boyarin\Spam\StopWords;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PromotionSpamTest extends TestCase
{
    #[DataProvider('messages')]
    public function testProductionClassifier(string $message, bool $expected): void
    {
        static $detector = null;
        $detector ??= new SpamDetector(
            (new ClassifierLoader(dirname(__DIR__).'/resources/classifiers'))->load(),
            new StopWords(dirname(__DIR__).'/resources/stop-words.json'),
        );

        self::assertSame($expected, $detector->isSpam($message));
    }

    public static function messages(): array
    {
        return [
            'reported loading praise' => ['Спасибо большое грузит!', true],
            'reported bot mentions' => ['@Dhm_0a3ec3ddbot  @Dhm_0d4da4a8bot  @Dhm_0f1f57ddbot', true],
            'reported VPN praise' => ['Помогли сильно!  Рабочий впн', true],
            'reported OTC advert' => [<<<'SPAM'
🔥 【GLOBAL OTC】FIAT FIRST ➡️ USDT SECOND!
❌ WARNING: No USDT = No Cooperation. Do not waste our time.
💎 WHY CHOOSE US?
WE PAY FIRST — Zero risk for real USDT holders.
SAFETY PERIOD — Extra security buffer to fully protect your account.
FUND OPTIONS — 100% Clean / Dedicated Gaming / Mixed Liquidity.
🌎 POWERFUL DISBURSAL (100+ Countries):
💵 USD | EUR | GBP | SGD (Western & SG)
🇨🇳 CNY | INR | BDT | PKR | KRW (Asia Hot)
🇦🇪 AED | TRY | MYR | THB | VND | IDR | BRL (Emerging)
🤝 THE RULE: We verify your USDT balance ➡️ We pay fiat ➡️ You send crypto.
📲 DM US NOW: @Payr_kai
If you can't send a message, you can send it to my bot.
: @payttlBot
SPAM, true],
            'rotated campaign bot' => ['@Dhm_012abc89bot', true],
            'loading praise punctuation' => ['Спасибо большое, грузит!!!', true],
            'unseen OTC offer' => ['GLOBAL OTC — USDT holders wanted. We pay fiat first, zero risk. DM @TradeDeskBot.', true],
            'unseen Russian offer' => ['Купим ваши USDT, оплатим рублями первыми. Для сделки напишите менеджеру @ExchangeDeskBot.', true],
            'unseen guaranteed return' => ['Пассивный доход в USDT каждый день, гарантируем прибыль. Подключайтесь к нашему боту!', true],
            'unseen balance scam' => ['Verify your USDT balance to activate clean liquidity. DM our OTC manager for cooperation.', true],
            'Laravel gratitude' => ['Спасибо большое, теперь Laravel грузит страницу без ошибок!', false],
            'technical loading question' => ['Спасибо большое! Почему грузит процессор после обновления PHP?', false],
            'bot integration' => ['Как настроить webhook для @ExampleSupportBot в Laravel?', false],
            'USDT amount storage' => ['Как хранить сумму USDT в Laravel: decimal или integer в минимальных единицах?', false],
            'USDT webhook' => ['Как проверить подпись webhook платежа USDT в контроллере Laravel?', false],
            'USDT API precision' => ['How do I parse USDT amounts without floating point errors in PHP?', false],
            'USDT test fixture' => ['В тестах API валюта USDT, как лучше замокать ответ провайдера?', false],
            'loading gratitude with context' => ['Спасибо большое, грузит страницу правильно после обновления Laravel.', false],
            'VPN test stand question' => ['Помогли сильно! Рабочий VPN для тестового стенда?', false],
            'unrelated hex username' => ['@Dhm_012abc89 помог разобраться с Eloquent', false],
        ];
    }
}
