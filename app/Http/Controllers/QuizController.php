<?php

namespace App\Http\Controllers;

use App\Quiz\Question;
use App\Quiz\QuizState;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QuizController extends Controller
{
    /**
     * @var QuizState
     */
    public QuizState $quiz;

    public Question $currentQuestion;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function startQuiz(Request $request)
    {
        $this->quiz = new QuizState();
        $this->quiz->countQuestions = $this->questions()->count();
        $this->quiz->start = true;
        $this->quiz->lastQuestion--;

        return $this->next($request);
    }

    public function next(Request $request)
    {
        $this->quiz ??= unserialize($request->session()->get('quiz'));

        $this->quiz->isIncorrect = false;
        $this->quiz->currentAnswers = [];
        $this->quiz->displayInfo = false;
        $this->quiz->userAnswer = '';
        $this->quiz->lastQuestion++;
        $this->quiz->stepProgressBar = $this->quiz->lastQuestion;

        if (! $this->questions()->has($this->quiz->lastQuestion)) {
            $this->quiz->finish = true;
            $this->currentQuestion = Question::recovery($this->quiz->currentQuestion);

            return turbo_stream_view($this->show($request));
        }

        $this->currentQuestion = $this->questions()->get($this->quiz->lastQuestion);
        $this->quiz->currentQuestion = $this->currentQuestion->toArray();

        return turbo_stream_view($this->show($request));
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function setAnswer(Request $request)
    {
        $userAnswer = $request->get('answer');
        $this->quiz = unserialize($request->session()->get('quiz'));

        $this->quiz->countAttempts++;
        $this->quiz->userAnswer = $userAnswer;
        $this->currentQuestion = Question::recovery($this->quiz->currentQuestion);

        if (! $this->currentQuestion->isCorrect($userAnswer)) {
            $this->quiz->countIncorrect++;
            $this->quiz->currentAnswers[] = $userAnswer;
            $this->quiz->live--;
            $this->quiz->isIncorrect = true;

            if ($this->quiz->live < 1) {
                $this->quiz->gameOver = true;
            }

            return turbo_stream_view($this->show($request));
        }
        $this->quiz->isIncorrect = false;
        $this->quiz->displayInfo = true;
        $this->quiz->countCorrects++;
        $this->quiz->stepProgressBar++;

        return turbo_stream_view($this->show($request));
    }

    /**
     * @throws \Exception
     */
    public function show(Request $request)
    {
        $request->session()->put('quiz', serialize($this->quiz));

        return view('quiz.quiz', [
            'quiz'               => $this->quiz,
            'currentQuestion'    => $this->currentQuestion ?? $this->questions()->get($this->quiz->lastQuestion),
            'currentStepPercent' => $this->currentStepPercent($this->quiz->stepProgressBar),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function index(Request $request)
    {
        return view('quiz.index');
    }

    /**
     * Для каждой эмоции у нас есть две ситуации, cчайным образом выбираем одну
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function questions(): Collection
    {
        return collect([
            Question::make(['Какова основная цель фреймворка Laravel?', 'Какой фреймворк акцентирует внимание на элегантном синтаксисе и призван делать процесс разработки приятным?'])
                ->answers(['Symfony', 'Django', 'Rails', 'Express', 'Laravel'])
                ->setCorrectAnswer('Laravel'),

            Question::make(['Какой компонент Laravel позволяет управлять операциями с базой данных упрощенным способом?', 'Какая функция в Laravel упрощает взаимодействие с базой данных?'])
                ->answers(['Eloquent ORM', 'Lumen', 'Blade', 'Artisan', 'Eloquent'])
                ->setCorrectAnswer('Eloquent ORM'),

            Question::make(['Какая функция Laravel предоставляет удобный способ определения логики авторизации в приложении?', 'Какой компонент Laravel упрощает задачи авторизации пользователей?'])
                ->answers(['Blade', 'Middleware', 'Eloquent ORM', 'Authentication', 'Policies'])
                ->setCorrectAnswer('Policies'),

            Question::make(['Какой инструмент командной строки в Laravel помогает с повторяющимися задачами, такими как миграции базы данных и их наполнение данными?', 'Какой компонент Laravel используется для задач в командной строке?'])
                ->answers(['Composer', 'Eloquent ORM', 'Blade', 'Artisan', 'Artisan'])
                ->setCorrectAnswer('Artisan'),

            Question::make(['Какая функция Laravel позволяет разработчикам создавать модульный, многократно используемый код?', 'Какое понятие Laravel способствует повторному использованию кода и его поддержке?'])
                ->answers(['Routing', 'Middleware', 'Blade', 'Controllers', 'Middleware'])
                ->setCorrectAnswer('Middleware'),
        ]);
    }

    public function currentStepPercent($stepProgressBar)
    {
        $currentStep = $stepProgressBar;

        if ($currentStep === 0) {
            return 0;
        }

        return $currentStep / $this->questions()->count() * 100;
    }
}
