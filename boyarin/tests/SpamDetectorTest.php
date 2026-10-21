<?php

declare(strict_types=1);

namespace Boyarin\Tests;

use AssistedMindfulness\NaiveBayes\Classifier;
use Boyarin\Spam\SpamDetector;
use Boyarin\Spam\StopWords;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SpamDetectorTest extends TestCase
{
    #[Test]
    #[DataProvider('messages')]
    public function detectsSpam(string $message, bool $expected): void
    {
        $classifier = new Classifier();
        $classifier->learn($message, SpamDetector::HAM);
        $classifier->learn('обычный вопрос по laravel', SpamDetector::HAM);
        $classifier->learn('Прuвет заработок криптовалюта', SpamDetector::SPAM);

        $path = sys_get_temp_dir().'/boyarin-stop-words-'.bin2hex(random_bytes(6)).'.json';
        file_put_contents($path, '{"words":["нужен только телефон"]}');

        self::assertSame($expected, (new SpamDetector($classifier, new StopWords($path)))->isSpam($message));

        unlink($path);
    }

    public static function messages(): array
    {
        return [
            'ham'        => ['А вот интересно, какова вероятность кражи токена?', false],
            'stop words' => ['Стабильный доход от 100$. Нужен только телефон', true],
            'emoji spam' => ['🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥', true],
        ];
    }
}
