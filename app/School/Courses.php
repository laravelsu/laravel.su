<?php

namespace App\School;

class Courses
{
    /**
     * Get the courses items.
     *
     * @return array
     */
    public static function items(): array
    {
        return [
            Teacher::make('Михаил Протасевич', '/img/community/protasevich.jpg', [
                new Course(
                    'Laravel Reverb',
                    'Современный подход к реализации веб-сокетов на Laravel',
                    '/img/ui/courses/reverb.svg',
                    'https://www.youtube.com/playlist?list=PLiOhsP3M5j5wJmOW-pd85abUB3iqpaiZy'
                ),
                new Course(
                    'Разработка пакета под Laravel',
                    'От инициализации и тестирования до управления ресурсами',
                    '/img/ui/courses/chest.svg',
                    'https://www.youtube.com/playlist?list=PLiOhsP3M5j5x_NCEhb09gnH_RCj-Vflxs'
                ),
                new Course(
                    'Laravel и его друзья',
                    'Полезные пакеты, решения и новости',
                    '/img/ui/courses/laravel.svg',
                    'https://www.youtube.com/playlist?list=PLiOhsP3M5j5wz0OoYum7LkXTEjKF_Ywb_'
                ),
            ]),

            Teacher::make('Данил Щуцкий', '/img/community/danil-shutsky.jpg', [
                new Course(
                    'Отношения в Eloquent',
                    'Основы применения различных связей',
                    '/img/ui/courses/relationship.svg',
                    'https://www.youtube.com/playlist?list=PLTucyHptHtTmEUKZhY40SrXPhUuPdp1bD'
                ),
                new Course(
                    'Под капотом',
                    'Как это работает? Давайте погрузимся во внутренности фреймворка',
                    '/img/ui/courses/chest.svg',
                    'https://www.youtube.com/playlist?list=PLTucyHptHtTnkmEwK0Yxxl-L9Z_8XOK6c'
                ),
                new Course(
                    'Laravel с нуля',
                    'Воплотите свои идеи после руководство для абсолютных новичков',
                    '/img/ui/courses/laravel.svg',
                    'https://www.youtube.com/playlist?list=PLTucyHptHtTkUbXaikXEmCWL8GradRx9I'
                ),
                new Course(
                    'MoonShine 2',
                    'Установка и применение админ-панели по шагам наглядно и понятно',
                    '/img/ui/courses/moonshine.svg',
                    'https://www.youtube.com/playlist?list=PLTucyHptHtTnFB4pLj1FpqEMwu7qv3xbG'
                ),
            ]),

            Teacher::make('Максим Орлов', '/img/community/orlov.jpg', [
                new Course(
                    'Деплой',
                    'Деплой (CI/CD) Laravel на хостинг автоматически (3 способа)',
                    '/img/ui/courses/deploy.svg',
                    'https://www.youtube.com/playlist?list=PLXCVm4GFpx5BNlRCGZqVFK1IMUampm3Q_'
                ),
                new Course(
                    'Laravel',
                    'Рассмотрим все возможности фреймворка на 2023 год.',
                    '/img/ui/courses/laravel.svg',
                    'https://www.youtube.com/playlist?list=PLXCVm4GFpx5CZf4X5ppNJTPsaGwSlBXLX'
                ),
                new Course(
                    'Laravel Helpers',
                    'Мои функции хелперы с которыми я работаю ежедневно.',
                    '/img/ui/courses/helpers.svg',
                    'https://www.youtube.com/playlist?list=PLXCVm4GFpx5DMQeuzyQwZW8QtslxsUxFy'
                ),
                new Course(
                    'Tailwind',
                    'Верстаем ВКонтакте с помощью инструмента Tailwind',
                    '/img/ui/courses/tailwind.svg',
                    'https://www.youtube.com/playlist?list=PLXCVm4GFpx5AjF_3jMD6tsDI6eS-yc92U'
                ),
            ]),

            Teacher::make('Дмитрий Афанасьев', '/img/community/afanasyev.jpg', [
                new Course(
                    'Git',
                    'Обязательно знать и уметь применять систему контроля версий',
                    '/img/ui/courses/git.svg',
                    'https://www.youtube.com/playlist?list=PLoonZ8wII66iUm84o7nadL-oqINzBLk5g'
                ),
                new Course(
                    'Эксперт PHP',
                    'В курсе будут рассмотрены наиболее популярные функции и механики языка PHP.',
                    '/img/ui/courses/php.svg',
                    'https://www.youtube.com/playlist?list=PLoonZ8wII66iZSicLNXhE4bxUYaKhIc-L'
                ),
                new Course(
                    'Основы Laravel',
                    'Пошаговый видеокурс по фреймворку Laravel. Версии фреймворка используемые в курсе: 5.7.2 - 8.*',
                    '/img/ui/courses/laravel.svg',
                    'https://www.youtube.com/playlist?list=PLoonZ8wII66iP0fJPHhkLXa3k7CMef9ak'
                ),
                new Course(
                    'Шаблоны проектирования',
                    'Рассмотрены и реализуйте паттерны на языке PHP.',
                    '/img/ui/courses/template.svg',
                    'https://www.youtube.com/playlist?list=PLoonZ8wII66hKbEvIVAZnp1h4CE-4Mtk4'
                ),
            ]),
        ];
    }
}
