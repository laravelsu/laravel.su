<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MetaNoIndex extends Component
{
    /**
     * Current domain to compare against.
     *
     * @var string
     */
    public string $expectedDomain;

    public function __construct()
    {
        $this->expectedDomain = parse_url(config('app.url'), PHP_URL_HOST);
    }

    /**
     * Check if the current URL matches the expected one.
     */
    public function shouldRender(): bool
    {
        $currentDomain = parse_url(request()->fullUrl(), PHP_URL_HOST);

        return $currentDomain !== $this->expectedDomain;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return '<meta name="robots" content="noindex, nofollow">';
    }
}
