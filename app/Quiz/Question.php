<?php

declare(strict_types=1);

namespace App\Quiz;

use Illuminate\Support\Str;

class Question
{
    /**
     * @var array
     */
    public array $answers = [];

    /**
     * @var string
     */
    public string $title = '';
    /**
     * @var string
     */
    public string $correctAnswer;

    /**
     * @var string
     */
    public string $description = '';

    /**
     * @param string $title
     */
    public function __construct(string $title, string $description = '')
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @param string|iterable $title
     * @param string          $description
     *
     * @return \App\Quiz\Question
     */
    public static function make(string|iterable $title, string $description = ''): Question
    {
        // если передан массив вопросов(ситуаций) - нужно выбрать один вопрос
        if (is_array($title)) {
            $key = array_rand($title);

            return new self($title[$key], $description);
        }

        return new self($title, $description);
    }

    /**
     * @param array $answers
     * @param int   $count
     *
     * @return $this
     */
    public function answers(array $answers, int $count = 4): Question
    {
        $this->answers = $count !== 0 && $count < count($answers) ? $this->randomValues($answers, $count) : $answers;

        return $this;
    }

    /**
     * @param string $correctAnswer
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setCorrectAnswer(string $correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;
        if (! in_array($correctAnswer, $this->answers)) {
            // нужно не только задать правильный ответ, но и добавить его в массив с вопросами
            $number = random_int(0, count($this->answers)); // правильный ответ положим в массив ответов на место $number
            array_splice($this->answers, $number, 0, $correctAnswer);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    /**
     * @param string $ansver
     *
     * @return bool
     */
    public function isCorrect(string $ansver): bool
    {
        return $ansver === $this->correctAnswer;
    }

    public function getTitle()
    {
        return Str::of($this->title)->markdown();
    }

    public function getDescription()
    {
        return Str::of($this->description)->markdown();
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @param $value
     *
     * @throws \Exception
     *
     * @return \App\Quiz\Question
     */
    public static function recovery($value)
    {
        return (new self($value['title']))
            ->answers($value['answers'], count($value['answers']))
            ->setCorrectAnswer($value['correctAnswer']);
    }

    protected function randomValues(array $arr, $count = 2): array
    {
        $result = [];
        $arrKeys = array_rand($arr, $count);
        if (is_array($arrKeys)) {
            foreach ($arrKeys as $key) {
                $result[] = $arr[$key];
            }

            return $result;
        }

        return $arr;
    }
}
