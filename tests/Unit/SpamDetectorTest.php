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
     * Data provider for messages and expected results.
     */
    public static function messageProvider()
    {
        return [
            ['А вот интересно кстати, какова вообще вероятность кражи токена?', false],
            ['Нужны партнеры в сферу (крипта) заработка. Пассивный доход от 10% в месяц. Подробности в ЛС', true],
            ['Стабильный доход от 100$ Нужен только телефон', true],
            ['блокчейн в ЛС', true],
            ['Крипто инвестиции', true],
        ];
    }
}
