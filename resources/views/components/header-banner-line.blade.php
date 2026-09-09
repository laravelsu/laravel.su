@if($showPyhnik)
    <aside class="pyhnik-banner" aria-label="Пыхник’26 — онлайн-конференция по PHP" id="header-pyhnik">
        <a class="pyhnik-banner__link container" href="https://pyhnik.phpyh.ru/" target="_blank" rel="noopener noreferrer">
            <span class="pyhnik-banner__brand">
                <img src="{{ asset('img/pyhnik-26.svg') }}" alt="Пыхник’26" width="656" height="148">
                <span class="pyhnik-banner__format">Онлайн-конференция</span>
            </span>

            <span class="pyhnik-banner__copy">
                <span class="pyhnik-banner__title">Неделя PHP.<br class="d-sm-none"> Встречаемся онлайн.</span>
                <span class="pyhnik-banner__description">10 докладов <span aria-hidden="true">/</span> 5 дней <span aria-hidden="true">/</span> Свои люди</span>
            </span>

            <span class="pyhnik-banner__date">
                <time datetime="2026-09-28">28 сентября</time> — <time datetime="2026-10-02">2 октября</time>
            </span>

            <span class="pyhnik-banner__action">
                <span class="d-none d-sm-inline">Программа и билеты</span>
                <span class="d-sm-none">Программа</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                    <path d="M5 19 19 5M5 5h14v14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="visually-hidden">Откроется в новой вкладке</span>
        </a>
    </aside>
@else
    <div class="bg-primary text-bg-primary bg-gradient text-center py-2 d-none d-lg-block" id="header-message" data-turbo-permanent>
        {!! $message !!}
    </div>
@endif
