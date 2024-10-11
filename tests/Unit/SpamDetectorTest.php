<?php

namespace Tests\Unit;

use App\Services\SpamDetector;
use Tests\TestCase;

class SpamDetectorTest extends TestCase
{
    /**
     * Test if the message is classified as spam.
     *
     * @dataProvider messageProvider
     */
    public function testIsSpam($message, $expected)
    {
        $spamDetector = new SpamDetector($message);
        $this->assertEquals($expected, $spamDetector->isSpam());
    }

    /**
     * Provides a set of messages and their expected spam classification.
     *
     * @return array
     */
    public static function messageProvider(): array
    {
        return [
            // Non-spam messages
            ['А вот интересно кстати, какова вообще вероятность кражи токена?', false],

            // Spam messages
            ['Нужны партнеры в сферу (крипта) заработка. Пассивный доход от 10% в месяц. Подробности в ЛС', true],
            ['Стабильный доход от 100$ Нужен только телефон', true],
            ['блокчейн в ЛС', true],
            ['Крипто инвестиции', true],
            ['18+', true],
            ['hamsterkombat', true],
            ['hamster', true],
            ['Прuвет', true],
        ];
    }

    /**
     * Test that special characters do not exceed word count for a given message.
     */
    public function testSpecialCharactersDoNotExceedWordCountWithEmoji(): void
    {
        $spamDetector = new SpamDetector('🍕 Прикольно, что ты тут делаешь? 🍣🍰');
        $this->assertFalse($spamDetector->hasTooManySpecialCharacters());
    }

    public function testSpecialCharactersDoNotExceedWordCountWithComplexMessage(): void
    {
        $spamDetector = new SpamDetector('Прuвет всем, хoчу предлoжuть реaльный дoпoлнuтельный зaрaбoтoк!
        - От 50$ в/зa день гaрaнтuрoвaнo
        - Чaс в день твoегo временu
        - Честнo u легaльнo, НЕ НАРКОТИКИ!!

        Еслu ты действuтельнo зauнтересoвaн в быстрoм u честнoм зaрaбoтке , пuшu + в ЛС!!!!');
        $this->assertFalse($spamDetector->hasTooManySpecialCharacters());
    }

    public function testSpecialCharactersDoNotExceedWordCountWithSingleEmoji(): void
    {
        $spamDetector = new SpamDetector('Спасибо 🍰');
        $this->assertFalse($spamDetector->hasTooManySpecialCharacters());
    }

    /**
     * Test that excessive special characters indicate spam for a given message.
     */
    public function testExcessiveSpecialCharactersIndicateSpam(): void
    {
        $spamDetector = new SpamDetector('🌿 💙💙💙💙🩵 🌿
        🌿    🩵🩵💙💙    🌿
        🔥ЛУЧШЕЕ КАЧЕСТВО СНГ🔥
        • ⚪️⚪️⚪️⚪️⚪️  •
        • ⚪️🟣⚪️ •
        • 🟣🟣⚪️⚪️🟣 •
        • 🟣⚪️⚪️ •
        • ⚪️⚪️⚪️ •
        • 🟣⚪️🟣⚪️🟣🟣⚪️ •
        • ⚪️⚪️⚪️⚪️⚪️  •
        • ⚪️🟣⚪️ •
        • 🟣🟣⚪️⚪️🟣 •
        • 🟣⚪️⚪️ •
        • ⚪️⚪️⚪️ •
        • 🟣⚪️🟣⚪️🟣🟣⚪️ •
        • ⚪️⚪️⚪️⚪️⚪️  •
        • ⚪️🟣⚪️ •
        • 🟣🟣⚪️⚪️🟣 •
        • 🟣⚪️⚪️ •
        • ⚪️⚪️⚪️ •
        • 🟣⚪️🟣⚪️🟣🟣⚪️ •
        ⚡️ ПОЛНЫЙ АССОРТИМЕНТ В БОТЕ⚡️

        ПО МНОГОЧИСЛЕННЫМ ПРОСЬБАМ ЗАПУСТИЛИ
        БОТА-АВТОПРОДАЖ В ТЕЛЕГРАММ📱
             💙@dendis_shoplk_bot💙

        👑ГАРАНТИЯ БЕЗОПАСНОСТИ 👑
        👑 LUX КАЧЕСТВО👑
        👑 Работаем на 🐙 с 2018 года! 👑
        👑РАБОТАЕМ ПО ВСЕЙ РФ 👑

        Оплата 💳/🪙/📩/

        🟣⚪️🟣⚪️ В течение 9-12 октября действует акция на розыгрыш пробников от 0.5 до 1.
        Для участия нужно сделать 1 покупку!
        🔩Проблемы с оплатой/кладом/сервисом? Пиши нам в поддержку👋
        🌿Все подробности в  БОТЕ/КАНАЛЕ 🌿

        🔴ПОКУПАТЬ ТУТ🔴
        @dendis_shoplk_bot
        @dendis_shoplk_bot
        @dendis_shoplk_bot');

        $this->assertTrue($spamDetector->hasTooManySpecialCharacters());
    }

    public function testDetectsExcessiveSpecialCharacters(): void
    {
        $spamDetector = new SpamDetector('🩸🅰️🅱️🩸🩸🅰️
⚔️⚔️⚔️⚔️⚔️⚔️⚔️⚔️
🔠🔠🔠🔠🔠 от 900 до 10000р. в день
🔤🔤🔤🔤🔤🔤🔤
🚗🚕🚙🚌🚎🏎🏎🚓
🖥 Связь: @michael_filll ‼️

📝2️⃣1️⃣➕');

        $this->assertTrue($spamDetector->hasTooManySpecialCharacters());
    }

    public function testMessageWithExcessiveSpecialCharacters(): void
    {
        $spamDetector = new SpamDetector('🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥');
        $this->assertTrue($spamDetector->hasTooManySpecialCharacters());
    }

    public function testMessageOneSpecialCharacters(): void
    {
        $spamDetector = new SpamDetector('🔥');
        $this->assertTrue($spamDetector->hasTooManySpecialCharacters());
    }
}
