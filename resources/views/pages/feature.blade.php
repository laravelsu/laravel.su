@extends('layout')
@section('title', "Возможности")
@section('description', '«Из коробки» Laravel предлагает элегантные решения для множества функций, необходимых всем современным приложениям. Пришло время создавать их!')

@section('content')
    <x-header image="/img/sign.svg">
        <x-slot:sup>Всё что нужно для достижения цели</x-slot>
        <x-slot:title>Одна платформа, множество путей.</x-slot>

        <x-slot:description>
            «Из коробки» Laravel предлагает элегантные решения для множества функций, необходимых всем современным
            приложениям. {{-- Пришло время создавать их! --}}
        </x-slot>

        <x-slot:actions>
            <a href="{{route('why-laravel')}}" class="btn btn-primary btn-lg px-4">Почему Laravel?</a>

            <a href="{{ route('courses') }}"
               class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">Обучение
                <x-icon path="i.arrow-right" class="bi" />
            </a>
        </x-slot>

    </x-header>

    <x-container data-controller="prism">
        <section class="mb-5 pb-md-5">
            <div class="bg-body-tertiary p-4 p-xl-5 rounded position-relative"
                 data-controller="tabs"
                 data-tabs-active-tab-class="bg-body-secondary"
                 data-tabs-index-value="1"
            >
                <div class="row d-flex mb-4 align-items-baseline">
                    <li class="col d-flex flex-column flex-lg-row gap-3 gap-lg-4 rounded p-3 p-xxl-4 align-items-center align-items-lg-start" id="first" data-tabs-target="tab" data-action="click->tabs#change:prevent">
                        <x-icon path="i.inertia" class="text-body-secondary flex-shrink-0" width="2rem" height="2rem"/>
                        <a href="#"
                           class="text-body-secondary text-decoration-none"
                           data-action="keydown.left->tabs#previousTab keydown.right->tabs#nextTab keydown.home->tabs#firstTab:prevent keydown.end->tabs#lastTab:prevent">
                            <h5 class="mb-0">Inertia</h5>
                            <small class="opacity-75 d-none d-lg-block">Усовершенствуйте Laravel с помощью React, Vue или Svelte</small>
                        </a>
                    </li>
                    <li class="col d-flex flex-column flex-lg-row gap-3 gap-lg-4 rounded p-3 p-xxl-4 align-items-center align-items-lg-start" id="second" data-tabs-target="tab" data-action="click->tabs#change:prevent">
                        <x-icon path="i.livewire" class="text-body-secondary flex-shrink-0" width="2rem" height="2rem"/>
                        <a href="#"
                           class="text-body-secondary text-decoration-none"
                           data-action="keydown.left->tabs#previousTab keydown.right->tabs#nextTab keydown.home->tabs#firstTab:prevent keydown.end->tabs#lastTab:prevent">
                            <h5 class="mb-0">Livewire</h5>
                            <small class="opacity-75 d-none d-lg-block">Реактивные шаблоны, построенные с помощью PHP</small>
                        </a>
                    </li>
                    <li class="col d-flex flex-column flex-lg-row gap-3 gap-lg-4 rounded p-3 p-xxl-4 align-items-center align-items-lg-start" id="third" data-tabs-target="tab" data-action="click->tabs#change:prevent">
                        <x-icon path="i.spa" class="text-body-secondary flex-shrink-0" width="2rem" height="2rem"/>
                        <a href="#"
                           class="text-body-secondary text-decoration-none"
                           data-action="keydown.left->tabs#previousTab keydown.right->tabs#nextTab keydown.home->tabs#firstTab:prevent keydown.end->tabs#lastTab:prevent">
                            <h5 class="mb-0">API</h5>
                            <small class="opacity-75 d-none d-lg-block">Создавайте мощные API быстрее, чем когда-либо</small>
                        </a>
                    </li>
                </div>


                <div class="text-balance">
                    <div class="d-none" data-tabs-target="panel">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="display-5 lh-1 fw-semibold mb-3 text-balance">Inertia</h4>
                                <p>Занимается маршрутизацией и передачей данных между серверной частью
                                и внешним интерфейсом Laravel — нет необходимости создавать API или
                                поддерживать два набора маршрутов. Легко
                                передавайте данные из вашей базы данных непосредственно в реквизиты
                                компонентов вашей внешней страницы,
                                используя все функции Laravel под рукой в ​​одном фантастическом
                                монорепозитории.</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-6">
       <pre class="rounded-3 my-0 h-100"><code language="js">
class UserController
{
    public function index()
    {
        $users = User::active()
            ->orderByName()
            ->get(['id', 'name', 'email']);

        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }
}
           </code>
                                </pre>
                            </div>
                            <div class="col-12 col-lg-6">



                                <pre class="rounded-3 my-0 h-100"><code language="js">
import Layout from './Layout'

export default function Users({ users }) {
  return (
    <Layout>
      {users.map(user => (
        <Link href={route('users.show', user)}>
          {user.name} ({user.email})
        </Link>
      ))}
    </Layout>
  )
}
                                </code></pre>
                            </div>
                        </div>


                        <div class="row my-3">
                            <div class="col-lg-6">
                                <p>Inertia дает вам опыт разработчика и простоту создания многостраничного приложения,
                                   отображаемого на сервере, с пользовательским интерфейсом и оперативностью JavaScript
                                   SPA.</p>

                                <p>Ваши внешние компоненты могут сосредоточиться на взаимодействии с пользователем, а не на
                                   вызовах API и манипулировании данными — больше не нужно вручную запускать HTTP-запросы и
                                   манипулировать ответами.
                                </p>
                                <p class="mb-lg-0">Inertia даже предлагает рендеринг на стороне сервера при начальной загрузке
                                                страницы для
                                                приложений, которые получают выгоду от поисковой оптимизации.
                                </p>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-4 border rounded">
                                    <h6 class="fw-bolder">Как это работает?</h6>

                                    <p>Первоначальная загрузка страницы вашего приложения вернет SPA на базе Inertia и
                                       реквизиты
                                       страницы в одном запросе. Последующие запросы от нажатия ссылок или отправки форм
                                       будут
                                       автоматически возвращать только те реквизиты страницы, которые необходимы.</p>

                                    <p class="mb-0">Когда вы развертываете новые ресурсы, Inertia автоматически выполнит
                                                    следующий запрос при полной
                                                    загрузке страницы, поэтому ваши пользователи будут иметь самые последние
                                                    ресурсы, не теряя ни
                                                    секунды.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-tabs-target="panel">


                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="display-5 lh-1 fw-semibold mb-3 text-balance">Livewire</h4>
                                <p>Современный способ создания динамических интерфейсов с использованием
                                   серверных шаблонов вместо JavaScript-фреймворков. Он сочетает в себе простоту и
                                   быстроту разработки серверного приложения с пользовательским опытом JavaScript SPA
                                   (Single Page Application). Вам нужно увидеть это, чтобы поверить.</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-6">
    <pre class="rounded-3 my-0 h-100"><code language="php">
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public function render()
    {
        $users = User::search($this->search)->get();

        return view('livewire.search', [
            'users' => $users,
        ]);
    }
}
                                </code></pre>
                            </div>
                            <div class="col-12 col-lg-6">

@php
$livewireViewCode = <<<'HTML'
<div>
    <input wire:model="search"
        type="text"
        placeholder="Search users..." />

    <ul>
        @foreach ($users as $user)
            <li>{{ $user->username }}</li>
        @endforeach
    </ul>
</div>
HTML;
@endphp

<!-- html комментарии, в которые обёрнут код ниже - часть синтаксиса плагина unescaped-markup,
не удалять, не добавлять пробелы или бругие символы между тогом code и комментарием -->
                            <pre class="rounded-3 my-0 h-100 language-markup" tabindex="0">

<code language="html" class="language-html">{{ \Illuminate\Support\Str::of($livewireViewCode)->trim() }}</code>
                            </pre>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-lg-6">
                                <p>
                                    При использовании Livewire вам не нужен JavaScript для управления DOM или состоянием
                                    - вы просто добавите его для некоторых продуманных взаимодействий. Alpine.js -
                                    идеальная легковесная JavaScript-библиотека для сочетания с вашим приложением на
                                    Livewire.
                                </p>

                                <p class="mb-lg-0">
                                    По мере изменения состояния вашего компонента Livewire, ваш фронтенд автоматически
                                    будет обновляться. Но Livewire не останавливается на этом. Поддержка реального
                                    времени для проверки данных, обработки событий, загрузки файлов, авторизации и
                                    многого другого включена.
                                </p>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-4 border rounded">
                                    <h6 class="fw-bolder">Как это работает?</h6>

                                    <p class="mb-0">
                                        Livewire отрисовывает ваш HTML на сервере с использованием языка шаблонов Blade.
                                        Он автоматически добавляет необходимый JavaScript, чтобы страница стала
                                        реактивной, а также автоматически перерисовывает компоненты и обновляет DOM при
                                        изменении данных.
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="d-none" data-tabs-target="panel">

                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="display-5 lh-1 fw-semibold mb-3 text-balance">Не нужен фронтенд? Нет проблем.</h4>
                                <p>Laravel - идеальное бэкенд API для ваших JavaScript SPA и мобильных приложений. Вы
                                получите доступ ко всем функциям Laravel, сохраняя рабочий процесс разработки фронтенда,
                                к которому вы привыкли.</p>
                            </div>
                        </div>


                        <div class="row g-4">
                            <div class="col-lg-6">
                                <pre class="rounded-3 h-100 my-0"><code language="php">
class UserController
{
    public function index()
    {
        return User::active()
            ->orderByName()
            ->paginate(25, ['id', 'name', 'email']);
    }
}
                                </code></pre>
                            </div>
                            <div class="col-12 col-lg-6">
                                <pre class="rounded-3 h-100 my-0">
                                    <code language="json">@verbatim
{
  "data": [
      {
        "id": 1,
        "name": "Taylor Otwell",
        "email": "taylor@laravel.com",
      },
      // ...
  ],
  "from": 1,
  "to": 25,
  "total": 50,
  "per_page": 25,
  "current_page": 1,
  "last_page": 2,
  "first_page_url": "https://api.laravel.app/users?page=1",
  "last_page_url": "https://api.laravel.app/users?page=2",
  "next_page_url": "https://api.laravel.app/users?page=2",
  "prev_page_url": null,
  "path": "https://api.laravel.app/users",
}@endverbatim</code></pre>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-lg-6">
                                <p>
                                    Для аутентификации вы можете использовать надежную аутентификацию на основе куки в
                                    Laravel. Или вы можете использовать Laravel Sanctum или Laravel Passport, если вы
                                    разрабатываете мобильное приложение или ваш фронтенд размещен отдельно от бэкенд
                                    API.
                                </p>
                            </div>
                            <div class="col-lg-6">
                            <p class="bg-body-secondary rounded p-4 mb-0">
                                Если ваше API работает в условиях больших нагрузок, сочетайте ваше приложение
                                Laravel с Laravel Octane и Laravel Vapor, чтобы обрабатывать ваш трафик без проблем.
                            </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="mb-5 pb-md-5">
            <div class="row">
            <div class="col-lg-6 my-5">
                <h2 class="display-5 fw-semibold mb-4 text-balance">
                    Погрузитесь прямо со старта.
                </h2>
                <p class="fw-normal mb-4 mb-lg-5 text-balance">
                    Независимо от того, предпочитаете ли вы Livewire или React,
                    стартовые наборы Laravel позволят вам сразу же приступить к
                    делу. За считанные минуты вы можете получить
                    полнофункциональное приложение, сочетающее Laravel и Tailwind с
                    выбранным вами интерфейсом.
                </p>
            </div>

                <div class="col-lg-6">
                    <img src="/img/ui/puzzle.svg" class="img-fluid d-none d-lg-block mx-auto">
                </div>

            </div>
            <div class="bg-body-tertiary rounded overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="p-5 d-flex flex-column gap-4">
                            <div class="col-10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 369 68" class="h-12 max-w-full"
                                     role="image">
                                    <title>Laravel Breeze</title>
                                    <path fill="#fff"
                                          d="M220.6 16.8v15.8c-2-4.2-6.2-7-11.3-7h-.3c-3.4 0-6.4 1.2-8.6 3.1l1-2.7h-10.8l-3.4 9.3L184 26h-17.4v.3c-1.3-.5-2.7-.7-4.2-.7-3.4 0-6.5 1.5-8.7 3.8v-4.1l-2.5.1c-1.3.1-2.5.3-3.6.8V26h-19.8v.3c-1.3-.5-2.7-.7-4.2-.7-6.7 0-12.2 5.8-12.2 12.9 0 .8.1 1.5.2 2.2H104V18H93.7v32.7h21.1v-3.3c2.2 2.5 5.4 4 8.8 4 1.5 0 2.9-.3 4.2-.8v.1h19.8v-12c0-1.8.9-2.7 2.8-2.8-.2.8-.2 1.6-.2 2.5 0 7.1 5.5 12.9 12.2 12.9 1.5 0 2.9-.3 4.2-.8v.1h10V34.9l6.1 15.8h9.2l4.2-10.8c.7 6.7 6.1 11.5 13.4 11.5 4.5 0 8.3-1.9 10.7-5.3l.4-.6v5.2h10V16.8h-10zm-96 24.8c-1.9 0-3.2-1.3-3.2-3.3 0-2 1.2-3.3 3.2-3.3s3.3 1.3 3.3 3.3c-.1 2.1-1.3 3.3-3.3 3.3zm38.8 0c-1.9 0-3.2-1.3-3.2-3.3 0-2 1.2-3.3 3.2-3.3s3.3 1.3 3.3 3.3c0 2.1-1.3 3.3-3.3 3.3z"/>
                                    <circle fill="#fff" cx="34" cy="34" r="34"/>
                                    <circle cx="34" cy="34" r="32.4" fill="#fcbf24"/>
                                    <path d="M17.8 34 34 17.8 50.3 34 34 50.3 17.8 34z" fill="#d97707"/>
                                    <path
                                        d="M57 11.1 34 34V17.9l6.7-6.7H57zM34 34l23 23V40.8L50.2 34H34zm-6.7 23 6.7-6.7V34L11.1 57h16.2zM11.1 27.3l6.7 6.7H34L11.1 11.1v16.2z"
                                        fill="#fef3c7"/>
                                    <path fill="#fff"
                                          d="M359.3 50.1c-3.5 0-6.3-1.1-8.5-3.2-2.1-2.1-3.2-5-3.2-8.6 0-2.3.5-4.3 1.4-6.1 1-2 2.3-3.4 4.1-4.4 1.5-.9 3.5-1.4 5.7-1.4 3 0 5.5 1 7.4 3.1 1.8 1.9 2.7 4.8 2.7 8.1v2.1h-15.3c.2 1.5.6 2.6 1.4 3.4 1 1.1 2.3 1.6 4.3 1.6s3.8-.7 5.6-2.1l1.6-1.3 2.4 4.8-.8.8c-1.1 1.1-2.5 1.7-3.9 2.3h-.2c-1.8.7-3.3.9-4.7.9zm3.7-15.4c-.2-.7-.5-1.3-1-1.7-.8-.8-1.7-1.1-3.1-1.1-1.5 0-2.6.4-3.5 1.2-.5.4-.9 1-1.1 1.7h8.7zm-46 15.4c-3.5 0-6.3-1.1-8.5-3.2s-3.2-5-3.2-8.6c0-2.3.5-4.3 1.4-6.1 1-2 2.3-3.4 4.1-4.4 1.5-.9 3.5-1.4 5.7-1.4 3.1 0 5.6 1 7.4 3 1.8 1.9 2.7 4.8 2.7 8.1v2.2h-15.3c.2 1.5.6 2.6 1.4 3.4 1 1.1 2.3 1.6 4.3 1.6s3.8-.7 5.6-2.1l1.6-1.3 2.4 4.8-.8.8c-1.1 1.1-2.5 1.7-3.9 2.3h-.2c-1.7.7-3.2.9-4.7.9zm3.6-15.4c-.2-.7-.5-1.3-1-1.7-.8-.8-1.7-1.1-3.1-1.1-1.4 0-2.5.4-3.4 1.2-.5.5-.9 1.1-1.1 1.7h8.6zm-25.5 15.4c-3.5 0-6.3-1.1-8.5-3.2s-3.2-5-3.2-8.6c0-2.3.5-4.3 1.4-6.1 1-2 2.3-3.4 4.1-4.4 1.5-.9 3.5-1.4 5.7-1.4 3.2 0 5.8 1 7.5 3 1.7 1.9 2.7 4.7 2.7 8.1v2.2h-15.3c.2 1.5.6 2.6 1.4 3.4 1 1.1 2.3 1.6 4.3 1.6s3.8-.7 5.6-2.1l1.6-1.3 2.4 4.8-.8.8c-1.1 1.1-2.5 1.7-3.9 2.3h-.2c-1.9.7-3.4.9-4.8.9zm3.7-15.4c-.2-.7-.5-1.3-1-1.7-.8-.8-1.7-1.1-3.1-1.1-1.4 0-2.5.4-3.4 1.2-.5.5-.9 1.1-1.1 1.7h8.6zM327.2 50v-3.9l11.6-13.9h-11.1V27H347v4l-11.4 13.6h11.9V50h-20.3zm-56.1 0V34c0-2-.1-3.8-.3-5.4l-.1-1.8h5.7l.1.9c.1 0 .1-.1.2-.1 1.3-.8 2.9-1.2 4.8-1.2.7 0 1.4 0 2.2.2l1.3.3-.3 5.5-1.9-.5c-.3-.1-.8-.1-1.5-.1-1.5 0-2.5.4-3.3 1.3-.7.9-1.1 2.2-1.1 3.5V50h-5.8zm-26.9 0V18.6H257c3.1 0 5.8.8 7.7 2.2 2.1 1.5 3.1 3.8 3.1 6.7 0 1.9-.5 3.6-1.5 4.9-.3.5-.7.9-1.2 1.3.5.4 1 .8 1.5 1.4l.1.1c1.1 1.5 1.7 3.3 1.7 5.5 0 2.9-1 5.3-3 6.9-1.9 1.6-4.7 2.4-8.1 2.4h-13.1zm12.9-5.5c2.8 0 3.9-.6 4.3-.9l.1-.1c.2-.2 1-.7 1-2.7 0-2.1-.6-4-5.3-4H250v7.7h7.1zm-.6-13.2c2 0 3.4-.3 4.2-1 .5-.4 1.1-1.1 1.1-2.7 0-1.2 0-3.6-5.3-3.6h-6.6v7.3h6.6z"/>
                                    <path
                                          d="M261.6 33.9c1.5-.5 2.6-1.3 3.4-2.4s1.2-2.4 1.2-3.9c0-2.4-.8-4.2-2.4-5.4s-3.9-1.9-6.7-1.9h-11.2v28.1h11.5c3 0 5.4-.7 7-2 1.6-1.3 2.4-3.2 2.4-5.7 0-1.8-.4-3.2-1.3-4.5-1.1-1.1-2.3-1.9-3.9-2.3zm-13.4-11.6h8.2c4.7 0 7 1.8 7 5.3 0 1.8-.5 3.1-1.8 4-1.2.9-3 1.3-5.3 1.3h-8.2V22.3zm14.3 22.5c-1.1.9-3 1.3-5.4 1.3h-8.9v-11h8.9c4.7 0 7 1.9 7 5.7.1 1.9-.5 3.2-1.6 4zm18.9-16.7c.7 0 1.2 0 1.8.1l-.1 2.2c-.5-.1-1.1-.1-1.9-.1-2 0-3.5.7-4.6 1.9-.9 1.2-1.5 2.8-1.5 4.6v11.6h-2.3V34c0-2-.1-3.9-.3-5.5h2.3l.3 3.6c.5-1.3 1.3-2.4 2.4-3.1s2.4-.9 3.9-.9zm13.1 0c-1.9 0-3.5.4-4.9 1.2-1.5.8-2.6 2-3.4 3.6s-1.2 3.4-1.2 5.4c0 3.2.9 5.7 2.7 7.4 1.8 1.8 4.2 2.7 7.3 2.7 1.5 0 2.8-.3 4.2-.7 1.3-.5 2.6-1.1 3.4-1.9l-.9-1.9c-2 1.6-4.2 2.4-6.6 2.4-2.4 0-4.2-.7-5.5-2.2-1.2-1.3-1.9-3.4-1.9-6.1v-.1H303v-.5c0-3-.8-5.4-2.3-7-1.3-1.5-3.5-2.3-6.2-2.3zm-6.6 8.2c.3-2 1.1-3.5 2.2-4.6 1.2-1.1 2.7-1.6 4.6-1.6s3.2.5 4.3 1.6c1.1 1.1 1.6 2.6 1.8 4.6h-12.9zm28.6-8.2c-1.9 0-3.5.4-4.9 1.2-1.5.8-2.6 2-3.4 3.6s-1.2 3.4-1.2 5.4c0 3.2.9 5.7 2.7 7.4 1.8 1.8 4.2 2.7 7.3 2.7 1.5 0 2.8-.3 4.2-.7 1.3-.5 2.6-1.1 3.4-1.9l-.9-1.9c-2 1.6-4.2 2.4-6.6 2.4-2.4 0-4.2-.7-5.5-2.2-1.2-1.3-1.9-3.4-1.9-6.1v-.1H325v-.5c0-3-.8-5.4-2.3-7-1.5-1.5-3.5-2.3-6.2-2.3zm-6.7 8.2c.3-2 1.1-3.5 2.2-4.6 1.2-1.1 2.7-1.6 4.6-1.6s3.2.5 4.3 1.6c1.1 1.1 1.6 2.6 1.8 4.6h-12.9zm22.2 10h13.8v2h-16.9v-1.6l13.5-16.2h-12.9v-1.9h15.9v1.8L332 46.3zm35.2-8.1v-.5c0-3-.8-5.4-2.3-7-1.5-1.6-3.5-2.6-6.2-2.6-1.9 0-3.5.4-4.9 1.2-1.5.8-2.6 2-3.4 3.6s-1.2 3.4-1.2 5.4c0 3.2.9 5.7 2.7 7.4 1.8 1.8 4.2 2.7 7.3 2.7 1.5 0 2.8-.3 4.2-.7 1.3-.5 2.6-1.1 3.4-1.9l-.9-1.9c-2 1.6-4.2 2.4-6.6 2.4s-4.2-.7-5.5-2.2c-1.2-1.3-1.9-3.4-1.9-6.1v-.1h15.4l-.1.3zm-12.9-6.5c1.2-1.1 2.7-1.6 4.6-1.6s3.2.5 4.3 1.6c1.1 1.1 1.6 2.6 1.8 4.6h-12.8c.2-2 .9-3.5 2.1-4.6zM101.6 43.1h10.8v5.2H96.1V20.4h5.5zM130.2 28.4V31c-1.5-1.9-3.6-3-6.6-3-5.4 0-9.8 4.6-9.8 10.5s4.5 10.5 9.8 10.5c3 0 5.1-1.2 6.6-3v2.3h5.3v-20h-5.3zM124.6 44c-3.1 0-5.5-2.3-5.5-5.7 0-3.4 2.3-5.7 5.5-5.7s5.7 2.3 5.7 5.7c-.1 3.4-2.4 5.7-5.7 5.7zM145.3 31.7v-3.4H140v20h5.3v-9.6c0-4.2 3.4-5.4 6.1-5.1v-5.8c-2.4.2-5 1.2-6.1 3.9zM169.1 28.4V31c-1.5-1.9-3.6-3-6.6-3-5.4 0-9.8 4.6-9.8 10.5s4.5 10.5 9.8 10.5c3 0 5.1-1.2 6.6-3v2.3h5.3v-20h-5.3zM163.4 44c-3.1 0-5.5-2.3-5.5-5.7 0-3.4 2.3-5.7 5.5-5.7s5.7 2.3 5.7 5.7c0 3.4-2.5 5.7-5.7 5.7zM187.3 42.4l-4.9-14h-5.6l7.6 19.9h6l7.7-19.9h-5.7l-5.1 14zM219.5 38.4c0-5.9-4.3-10.5-10.4-10.4-6.3 0-10.7 4.6-10.7 10.5S202.8 49 209.5 49c3.9 0 6.9-1.6 8.8-4.3l-4.2-2.4c-.8 1.2-2.4 2-4.5 2-2.7 0-5-1.1-5.7-3.8h15.4c.1-.7.2-1.3.2-2.1zM204 36.5c.5-2.6 2.4-4 5.3-4 2.2 0 4.3 1.2 5 4H204zM223 19.2h5.3v29.1H223z"/>
                                </svg>

                            </div>

                            <p class="mb-0">Легкий стартовый комплект, который включает в себя шаблоны
                               управления
                               профилями пользователей для аутентификации в стиле Tailwind.</p>

                            <ul class="d-flex flex-column gap-2">
                                <li>Регистрация пользователя и вход в систему</li>
                                <li>Сброс пароля</li>
                                <li>Подтверждение адреса электронной почты</li>
                                <li>Управление профилями пользователей</li>
                                <li>Blade или Inertia (с Vue или React)</li>
                                <li>Дополнительная поддержка TypeScript</li>
                                <li>Дополнительная поддержка темного режима</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-7 overflow-hidden">
                        <img src="https://laravel.com/img/frontend/breeze-profile.png" class="mt-5 rounded-top-4 border-top border-start"
                             height="600px"/>
                    </div>
                </div>
                <div class="opacity-50">
                <hr class="mt-0">
                </div>

                <div class="p-4 p-xl-5">
                    <div class="row align-items-start">
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <div class="bg-body-secondary rounded p-4">
                            <p class="mb-0 opacity-75">
                                Настраивать окружение для новичка может быть непростой задачей.
                                Однако, есть несколько простых и удобных способов быстро и легко
                                запустить Laravel и сосредоточиться на разработке приложения.
                            </p>
                        </div>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/img/ui/apple.svg" class="img-fluid">
                                    <h4 class="fw-semibold mb-0 text-body-emphasis">Laravel для Mac</h4>
                                </div>
                                <p class="mb-0">
                                    Laravel Valet предоставляет простой и минималистичный
                                    способ настройки вашей среды разработки для
                                    запуска приложений, а также обеспечивает доступ к ним через <code>*.test</code>
                                    домен.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/img/ui/docker.svg" class="img-fluid">
                                    <h4 class="fw-semibold mb-0 text-body-emphasis">Laravel для Docker</h4>
                                </div>
                                <p class="mb-0">
                                    Если вам нужна гибкость и изоляция, Laravel Sail предоставляет легкий
                                    интерфейс командной строки для работы с Docker. Даже если у вас нет опыта работы с
                                    Docker.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-container>


    <div class="bg-dark-subtle text-white py-5" style="background-image: url('/img/bg-packages.svg')" data-bs-theme="dark">
        <x-container>
            <div class="col-12">
                <div class="row g-4 g-md-5 py-5 row-cols-1 row-cols-lg-2">
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/route.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Маршрутизация</h4>
                            <p class="opacity-75 small mb-0">
                                Маршрутизация (Routing) позволяет определить, как приложение должно отвечать на разные
                                URL-адреса. Это позволяет легко настраивать маршруты для обработки запросов и
                                определять, какие действия и контроллеры должны быть вызваны при поступлении запроса.
                            </p>
                            <a href="{{ route('docs',['page'=>'routing']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/blade.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Шаблоны Blade</h4>
                            <p class="opacity-75 small mb-0">
                                Вставляйте переменные, используйте условия, циклы и другие
                                операции в шаблонах, что делает их более читабельными и удобными для разработки.
                            </p>
                            <a href="{{ route('docs',['page'=>'blade']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/authentication.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Аутентификация</h4>
                            <p class="opacity-75 small mb-0">
                                Аутентификация (Authentication) в Laravel предоставляет простой и удобный способ
                                проверки подлинности пользователей. С помощью встроенных функций аутентификации вы
                                можете легко добавить систему регистрации, входа и выхода из системы на свой веб-сайт
                                Laravel.
                            </p>
                            <a href="{{ route('docs',['page'=>'authentication']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/authorization.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Авторизация</h4>
                            <p class="opacity-75 small mb-0">
                                Авторизация (Authorization) в Laravel позволяет контролировать доступ пользователей к
                                определенным ресурсам или действиям. Это позволяет легко определить, какие пользователи
                                имеют право выполнять определенные операции в вашем приложении.
                            </p>
                            <a href="{{ route('docs',['page'=>'authorization']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/terminal.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Artisan Console</h4>
                            <p class="opacity-75 small mb-0">
                                Вы можете создавать миграции, запускать тесты, управлять
                                базой данных, генерировать код и многое другое с помощью Artisan. Команды Artisan
                                упрощают разработку, улучшают производительность и помогают взаимодействовать с вашим
                                приложением Laravel из командной строки.
                            </p>
                            <a href="{{ route('docs',['page'=>'artisan']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div class="d-inline-flex align-items-center justify-content-center me-3">
                            <img src="/icons/tests.svg">
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="text-body-emphasis fw-bold mb-0">Тестирование</h4>
                            <p class="opacity-75 small mb-0">
                                Встроенная система тестирования Laravel, использующая PHPUnit, обеспечивает удобные инструменты для
                                создания и выполнения тестовых сценариев. Вы можете тестировать маршруты, контроллеры,
                                модели и другие компоненты вашего приложения, чтобы гарантировать их работоспособность и
                                соответствие ожиданиям.
                            </p>
                            <a href="{{ route('docs',['page'=>'testing']) }}"
                               class="link-body-emphasis fw-semibold text-decoration-none icon-link icon-link-hover">
                                Подробнее
                                <x-icon path="i.arrow-right" class="bi" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </x-container>
    </div>


    <x-container data-controller="prism">

        <section class="pb-md-5 mb-5">

            <div class="row g-4 g-md-5">
                <div class="col-lg-6 mb-5">
                    <h2 class="display-5 fw-semibold mb-4 text-balance">Удобная работа с данными</h2>
                    <p class="fw-normal mb-4 mb-lg-5 text-balance">
                        Laravel имеет мощные инструменты для работы с базами данных.
                        Он поддерживает широкий спектр СУБД, включая MySQL, MariaDB, PostgreSQL, SQL Server и SQLite.

                        Вот несколько ключевых возможностей для работы с базой данных в Laravel:
                    </p>

                    <div class="bg-body-tertiary p-4 p-xl-5 rounded d-flex flex-column gap-4">
                        <h4 class="fw-bold">Eloquent ORM</h4>
                        <p class="mb-0">Не бойтесь работать с базами данных! Laravel позволяет легко
                                        взаимодействовать с данными вашего приложения. Создавайте модели, миграции и
                                        связи между ними в несколько простых шагов:</p>

                        <pre><code language="text">php artisan make:model Invoice --migration</code></pre>

                        <p class="mb-0">После определения структуры модели и ее отношений, можно легко взаимодействовать с базой
                           данных, используя мощный и выразительный синтаксис Eloquent:</p>

                        <pre><code language="php">// Создание связанной модели ...
$user->invoices()->create(['amount' => 100]);

// Обновление модели ...
$invoice->update(['amount' => 200]);

// Получение моделей ...
$invoices = Invoice::unpaid()
    ->where('amount', '>=', 100)
    ->get();

// Удобный API для взаимодействия ...
$invoices->each->pay();</code></pre>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="/img/ui/data.svg" class="img-fluid img-fluid d-none d-lg-block mx-auto">

                    <div class="bg-body-tertiary p-4 p-xl-5 rounded d-flex flex-column gap-4">
                        <h4 class="fw-bold mb-0">Миграции базы данных</h4>

                        <p class="mb-0">Миграции в Laravel - это аналог контроля версий для вашей базы данных. Они позволяют вашей
                           команде определить и поделиться структурой вашей базы данных:</p>

                        <pre><code language="php">// Создание таблицы "flights"
Schema::create('flights', ...);

// Установите столбец primary ключа как UUID
$table->uuid('id')->primary();

// Установите ограничение внешнего ключа
$table->foreignUuid('airline_id')
    ->constrained();

// Добавьте столбец для названия рейса
$table->string('name');

// Добавьте временные метки
$table->timestamps();
</code></pre>
                    </div>

                </div>
            </div>
        </section>


        <section class="mb-5 pb-md-5">
            <div class="row g-4 g-md-5">
                <div class="col-lg-6 mb-5">
                    <h2 class="display-5 fw-semibold mb-4 text-balance">Максимальная эффективность</h2>
                    <p class="fw-normal mb-4 mb-lg-5 text-balance">
                        Позвольте своему приложению работать с максимальной эффективностью благодаря очередям в Laravel.
                        Независимо от того, нужно ли обрабатывать длительные задачи, отправлять уведомления или обновлять
                        данные, очереди позволят вам добиться максимальной пропускной способности и отзывчивости в вашем
                        приложении.
                    </p>

                    <div class="bg-body-tertiary p-4 p-xl-5 rounded d-flex flex-column gap-4">
                        <h4 class="fw-bold">Job Queues</h4>
                        <p class="mb-0">Очереди задач (Job Queues) в Laravel позволяют вам перенести медленные задачи в
                                        фоновый режим, сохраняя отзывчивость веб-запросов. Пример
                                        использования:</p>

                        <pre><code language="php">$podcast = Podcast::create(/* ... */);

ProcessPodcast::dispatch($podcast)
    ->onQueue('podcasts');</code></pre>

                        <p class="mb-0">Вы можете запускать столько процессов очередей, сколько нужно для обработки вашей нагрузки:</p>

                        <pre><code language="bash">php artisan queue:work redis --queue=podcasts</code></pre>
                    </div>
            </div>
                <div class="col-lg-6">
                    <img src="/img/ui/crane.svg" class="img-fluid img-fluid d-none d-lg-block mx-auto">

                    <div class="bg-body-tertiary rounded">
                        <img src="/img/ecosystem/horizon.png" class="img-fluid rounded-top mb-3">

                        <div class="d-flex flex-column gap-4 px-5 pb-5 pt-3">
                            <h4 class="fw-bold">Horizon</h4>
                            <p>
                                Для удобного контроля и отслеживания очередей используйте Laravel Horizon.
                                Horizon предоставляет красивую панель управления и конфигурацию через код для ваших
                                очередей, работающих на Redis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </x-container>


    <x-call-to-action link="{{ route('library') }}" text="Перейти в библиотеку">
        <x-slot:title>Откройте дверь к мастерству</x-slot>

        <x-slot:description>
            В нашей библиотеке вы обнаружите сокровища знаний: чистый код, стратегии безопасности, методы оптимизации и
            многое другое. Углубитесь в эти ресурсы, чтобы стать выдающимся разработчиком.
        </x-slot>
    </x-call-to-action>
@endsection
