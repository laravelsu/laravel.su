<?php

namespace App\Quiz;

class QuizState implements \JsonSerializable
{
    public string $title = '';
    public string $userAnswer = '';
    public int $lastQuestion = 0;
    public int $stepProgressBar = 0;
    public array $currentQuestion = [];
    public array $currentAnswers = [];
    public bool $start = false;
    public int $countAttempts = 0;
    public int $countCorrects = 0;
    public int $countIncorrect = 0;
    public int $countQuestions = 0;
    public int $live = 3;
    public bool $finish = false;
    public bool $displayInfo = false;
    public bool $gameOver = false;
    public bool $isIncorrect = false;

    /**
     * Convert the object into a JSON serializable array.
     *
     * @return array The array representation of the object.
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * Create a new QuizState object from JSON string.
     *
     * @param string $json The JSON string to deserialize.
     * @return QuizState The deserialized QuizState object.
     */
    public static function fromJson(string $json): QuizState
    {
        $data = json_decode($json, true);

        $quizState = new self();

        // Iterate through the decoded data and set properties of the object.
        foreach ($data as $key => $value) {
            if (property_exists($quizState, $key)) {
                $quizState->$key = $value;
            }
        }

        return $quizState;
    }

}
