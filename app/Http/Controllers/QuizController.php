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

    /**
     * Display the welcome page.
     *
     * @throws \Exception
     */
    public function index()
    {
        return view('quiz.index');
    }

    /**
     * Set collection of questions and start the quiz.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function start()
    {
        $this->quiz = new QuizState($this->questions());

        return $this->show();
    }

    /**
     * Move to the next question.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function next(Request $request)
    {
        $this->quiz ??= unserialize($request->session()->get('quiz'));

        $this->quiz->next();

        return $this->show();
    }

    /**
     * Process user's answer.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function answer(Request $request)
    {
        $userAnswer = $request->get('answer');
        $this->quiz = unserialize($request->session()->get('quiz'));

        $this->quiz->applyAnswer($userAnswer);

        return $this->show();
    }

    /**
     * Display the quiz page.
     *
     * @throws \Exception
     */
    protected function show()
    {
        session()->put('quiz', serialize($this->quiz));

        return turbo_stream_view(view('quiz.quiz', [
            'quiz'               => $this->quiz,
            'currentQuestion'    => $this->quiz->question(),
        ]));
    }

    /**
     * Generate questions for the quiz.
     *
     * Randomly choose one situation for each emotion.
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function questions(): Collection
    {
        return collect([
            Question::make(['### MVC Overview

- **Model**: Represents the data and the business rules concerning the data. This includes data persistence, validation, business logic, and authentication.

- **View**: Presents data to the user in a specific format. The view receives data from the model and user interactions from the controller.

- **Controller**: Acts as an intermediary between models and views. It processes user input, interacts with the model, and selects the view to display.
'])
                ->answers(['**Controller**: Acts as an intermediary between models and views. It processes user input, interacts with the model, and selects the view to display.', 'Django', 'Rails', 'Express', 'Laravel'])
                ->setCorrectAnswer('**Controller**: Acts as an intermediary between models and views. It processes user input, interacts with the model, and selects the view to display.'),

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
}
