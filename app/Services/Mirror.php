<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;

class Mirror
{
    /**
     * Проверяет, является ли текущий запрос зеркалом основного сайта.
     *
     * @return bool
     */
    public function hasMirror(): bool
    {
        return $this->isMirror(Request::fullUrl(), config('app.url'));
    }

    /**
     * Определяет, является ли URL зеркалом по домену.
     *
     * @param string $currentUrl
     * @param string $baseUrl
     *
     * @return bool
     */
    protected function isMirror(string $currentUrl, string $baseUrl): bool
    {
        return parse_url($baseUrl, PHP_URL_HOST) !== parse_url($currentUrl, PHP_URL_HOST);
    }
}
