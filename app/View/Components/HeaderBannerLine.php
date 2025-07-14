<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderBannerLine extends Component
{
    public string $message;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->message = collect([
            sprintf('Любите загадки? Событие еще доступно на  <a href="%s" class="text-white link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">сайте</a>.', route('quiz.open')),
            sprintf('Подписывайтесь на наш <a href="%s" class="text-white link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Telegram канал</a> и будьте в курсе всех событий.', config('services.telegram.channel_url')),
            sprintf('Поддержите проект сделав <a href="%s" class="text-white link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">пожертвование</a>.', route('donate')),
            sprintf('Новая <a href="%s" class="text-white link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">PHP-конференция</a> для всех', 'https://conf.phpyh.ru/')
        ])->random();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header-banner-line');
    }
}
