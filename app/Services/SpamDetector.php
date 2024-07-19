<?php

namespace App\Services;

use AssistedMindfulness\NaiveBayes\Classifier;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpamDetector
{
    public const SPAM = 'spam';
    public const HAM = 'ham';

    private $stopWords = [
        'в личку', 'писать в лc', 'пишите в лс', 'в лuчные сообщенuя',
        'личных сообщениях', 'заработок удалённо', 'заработок в интернете',
        'заработок в сети', 'для yдaлённoгo зaрaбoткa', 'детали в ЛС',
        'Ищу партнеров', 'криптовалюта', 'пассивный доход', 'пассивный заработок',
        'партнерская программа', 'быстрые деньги', 'работа на дому',
        'инвестиции', 'финансовая независимость', 'заработок без вложений',
        'много денег сразу', 'легкий заработок', 'увеличение прибыли',
        'сомнительные схемы', 'способы заработка', 'работа без опыта',
        'получи доход', 'обогащение в интернете', 'заработок на кликах',
        'маркетинговые сети', 'продажа абонементов', 'продажа товаров',
        'заработок на опросах', 'финансовая свобода', 'интернет-маркетинг',
        'пассивные инвестиции', 'интернет-предпринимательство',
        'денежный поток', 'финансовый успех', 'продажа продукции',
        'финансовые советы', 'онлайн-бизнес', 'продажа услуг',
        'бонусная программа', 'маркетинг в интернете', 'маркетинговые инструменты',
        'пассивный доход в сети', 'прибыльные схемы', 'финансовые инвестиции',
        'заработок на вложениях', 'консультации по заработку',
        'финансовая консультация', 'заработок на сайтах', 'доход без риска',
        'финансовые возможности', 'инвестирование средств', 'партнерские сети',
        'финансовые инструменты', 'финансовые стратегии', 'заработок на криптовалюте',
        'биткоин', 'эфириум', 'трейдинг', 'доход в сети', 'увеличение капитала',
        'заработок на рефералах', 'финансовый рост', 'увеличение дохода',
        'инвестиции в интернете', 'заработок на акциях', 'инвестиционные возможности',
        'вложения с доходом', 'инвестиционный рост', 'пассивный доход на автопилоте',
        'финансовый успех в интернете', 'блокчейн', 'инвестиции в криптовалюту',
        'инвестирование в фонды', 'финансовая стабильность', 'биржевая торговля',
        'пассивный доход без риска', 'финансовые решения', 'инвестирование в недвижимость',
        'заработок на форексе', 'инвестирование в доллары', 'успешный заработок',
        'финансовая защита', 'инвестиции в будущее', 'реальный заработок',
        'финансовый инструментарий', 'инвестиции в золото', 'инвестиции в криптовалютные фонды',
        'увеличение прибыли в интернете', 'инвестирование в акции',
        'финансовая безопасность', 'нужен только телефон', 'стабильный доход',
        'бесплатное обучение', '18+', '18 лет', 'hamsterkombat', 'hamster',
    ];

    /**
     * Constructor to initialize the SpamDetector with a message.
     *
     * @param string $message The message to analyze
     */
    public function __construct(private string $message) {}

    /**
     * Check if the message contains any stop words.
     *
     * @return bool True if stop words are found, otherwise false
     */
    public function containsStopWords(): bool
    {
        return Str::of($this->message)->contains($this->stopWords, true);
    }

    /**
     * Checks if the message contains an excessive amount of special characters.
     * For example, the proportion of special characters should not exceed a given threshold (default is 2%).
     *
     * @param float $threshold
     *
     * @return bool
     */
    public function hasExcessiveUnicodeCharacters(float $threshold = 0.02): bool
    {
        // Length of the message including special characters
        $withUnicode = Str::of($this->message)
            ->replaceMatches('/^[^\p{L}\p{N}\p{Z}\p{P}]+|[^\p{L}\p{N}\p{Z}\p{P}]+$/u', '') // without start and end special characters (emoji, etc.)
            ->length();

        // Length of the message without special characters
        $withOutUnicode = Str::of($this->message)
            ->replaceMatches('/[^\p{L}\p{N}\p{Z}\p{P}]/u', '')
            ->length();

        // Message contains only emoji
        if ($withOutUnicode < 1) {
            return false;
        }

        // Difference in length
        $unicodeLength = $withUnicode - $withOutUnicode;

        // Proportion of special characters in the message
        $unicodePercentage = $unicodeLength / $withUnicode;

        // Check if the proportion of special characters exceeds the given threshold
        return $unicodePercentage > $threshold;
    }

    /**
     * Check if the message is spam using a Naive Bayes classifier.
     *
     * @return bool True if classified as spam, otherwise false
     */
    public function checkByClassifier(): bool
    {
        $classifier = Cache::remember('spam-classifier', now()->addDays(7), function () {
            $classifier = new Classifier();

            $classifier->uneven();

            $this
                ->trainClassifier($classifier, 'spam.json', static::SPAM)
                ->trainClassifier($classifier, 'ham.json', static::HAM);

            return $classifier;
        });

        return $classifier->most($this->message) === static::SPAM;
    }

    /**
     * Train the Naive Bayes classifier with messages from a JSON file.
     *
     * @param \AssistedMindfulness\NaiveBayes\Classifier $classifier The classifier instance
     * @param string                                     $fileName   The path to the JSON file containing messages
     * @param string                                     $label      The label to assign to the messages (spam or ham)
     *
     * @return self
     */
    private function trainClassifier(Classifier $classifier, string $fileName, string $label): self
    {
        $messages = json_decode(Storage::disk('classifiers')->get($fileName));

        foreach ($messages as $message) {
            $classifier->learn($message, $label);
        }

        return $this;
    }

    /**
     * Check if the message is classified as spam by either containing stop words or the classifier.
     *
     * @return bool True if classified as spam, otherwise false
     */
    public function isSpam()
    {
        if ($this->hasExcessiveUnicodeCharacters()) {
            return true;
        }

        if ($this->containsStopWords()) {
            return true;
        }

        if ($this->checkByClassifier()) {
            return true;
        }

        return false;
    }
}
