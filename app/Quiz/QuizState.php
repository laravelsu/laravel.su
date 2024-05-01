<?php

namespace App\Quiz;

use Illuminate\Support\Collection;

class QuizState implements \JsonSerializable
{
    /**
     * Number of lives/attempts.
     */
    public const LIVE = 3;

    /**
     * Current step of the quiz.
     *
     * @var int
     */
    public int $step = 0;

    /**
     * User's answer to the previous question.
     *
     * @var string
     */
    public string $userAnswer = '';

    /**
     * Current incorrect answers given by the user for the current question
     * (to avoid re-answering the same question).
     *
     * @var array
     */
    public array $incorrectAnswers = [];

    /**
     * Number of lives/attempts.
     *
     * @var int
     */
    public int $live = self::LIVE;

    public bool $displayInfo = false;
    public bool $isIncorrect = false;

    /**
     * Collection of questions for the quiz.
     *
     * @var \Illuminate\Support\Collection|null
     */
    public ?Collection $questions;

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
     * Create a new QuizState object from a JSON string.
     *
     * @param string $json The JSON string to deserialize.
     *
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

    /**
     * Move to the next question.
     *
     * @return $this
     */
    public function next(): static
    {
        $this->isIncorrect = false;
        $this->incorrectAnswers = [];
        $this->displayInfo = false;
        $this->userAnswer = '';
        $this->step++;

        return $this;
    }

    /**
     * Get the current question.
     *
     * @return \App\Quiz\Question|null
     */
    public function question(): ?Question
    {
        return $this->questions->get($this->step);
    }

    /**
     * Initialize the QuizState object with questions.
     *
     * @param \Illuminate\Support\Collection|null $questions
     */
    public function __construct(?Collection $questions = null)
    {
        $this->questions = $questions;
    }

    /**
     * Calculate the current step's percentage.
     *
     * @return float|int
     */
    public function currentStepPercent(): float|int
    {
        $currentStep = $this->step + 1;

        if ($currentStep === 0) {
            return 0;
        }

        return $currentStep / $this->questions->count() * 100;
    }

    /**
     * Check if all questions have been asked.
     *
     * @return bool
     */
    public function isFinish(): bool
    {
        return !$this->questions->has($this->step);
    }

    /**
     * Apply user's answer.
     *
     * @param mixed $answer The user's answer to the current question.
     *
     * @return $this
     */
    public function applyAnswer($answer): static
    {
        $this->userAnswer = $answer;

        if (!$this->question()->isCorrect($answer)) {
            $this->incorrectAnswers[] = $answer;
            $this->live--;
            $this->isIncorrect = true;
        } else {
            $this->isIncorrect = false;
            $this->displayInfo = true;
        }

        return $this;
    }

    /**
     * Check if the user has run out of lives/attempts.
     *
     * @return bool
     */
    public function isDead(): bool
    {
        return $this->live < 1;
    }

    /**
     * Check if the given answer is in the list of incorrect answers.
     *
     * @param string $answer The answer to check.
     * @return bool True if the answer is incorrect, false otherwise.
     */
    public function hasIncorrectAnswer(string $answer): bool
    {
        return in_array($answer, $this->incorrectAnswers, true);
    }

}
