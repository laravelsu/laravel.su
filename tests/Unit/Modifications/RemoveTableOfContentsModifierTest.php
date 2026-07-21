<?php

namespace Tests\Unit\Modifications;

use App\View\Modifications\RemoveTableOfContentsModifier;
use PHPUnit\Framework\TestCase;

class RemoveTableOfContentsModifierTest extends TestCase
{
    public function testItRemovesOpeningTableOfContents(): void
    {
        $modifier = new RemoveTableOfContentsModifier;

        $html = <<<'HTML'
            <ul>
                <li><a href="#introduction">Введение</a></li>
                <li><a href="#laravel-boost">Laravel Boost</a>
                    <ul>
                        <li><a href="#installation">Установка</a></li>
                    </ul>
                </li>
            </ul>
            <p>Laravel находится в уникальной позиции.</p>
            <h2>Введение</h2>
        HTML;

        $modifiedHtml = $modifier->handle($html, function ($content) {
            return $content;
        });

        $this->assertStringNotContainsString('<a href="#introduction">Введение</a>', $modifiedHtml);
        $this->assertStringContainsString('<p>Laravel находится в уникальной позиции.</p>', $modifiedHtml);
        $this->assertStringContainsString('<h2>Введение</h2>', $modifiedHtml);
    }

    public function testItRemovesTableOfContentsImmediatelyAfterPageHeading(): void
    {
        $modifier = new RemoveTableOfContentsModifier;

        $html = <<<'HTML'
            <h1>Frontend</h1>
            <ul>
                <li><a href="#introduction">Введение</a></li>
                <li><a href="#using-php">Использование PHP</a></li>
            </ul>
            <p><a name="introduction"></a></p>
            <h2>Введение</h2>
            <p>Laravel предоставляет инструменты для frontend-разработки.</p>
        HTML;

        $modifiedHtml = $modifier->handle($html, fn ($content) => $content);

        $this->assertStringContainsString('<h1>Frontend</h1>', $modifiedHtml);
        $this->assertStringNotContainsString('<a href="#introduction">Введение</a>', $modifiedHtml);
        $this->assertStringContainsString('<h2>Введение</h2>', $modifiedHtml);
    }

    public function testItKeepsRegularLists(): void
    {
        $modifier = new RemoveTableOfContentsModifier;

        $html = <<<'HTML'
            <p>Laravel находится в уникальной позиции.</p>
            <ul>
                <li><a href="/docs/13.x/boost">Laravel Boost</a></li>
                <li>Обычный пункт списка</li>
            </ul>
        HTML;

        $modifiedHtml = $modifier->handle($html, function ($content) {
            return $content;
        });

        $this->assertStringContainsString('<a href="/docs/13.x/boost">Laravel Boost</a>', $modifiedHtml);
        $this->assertStringContainsString('<li>Обычный пункт списка</li>', $modifiedHtml);
    }

    public function testItKeepsDocumentsWithoutTableOfContents(): void
    {
        $modifier = new RemoveTableOfContentsModifier;

        $html = <<<'HTML'
            <p><a name="introduction"></a></p>
            <h2>Введение</h2>
            <p>Blade - это простой, но мощный движок шаблонов.</p>
        HTML;

        $modifiedHtml = $modifier->handle($html, function ($content) {
            return $content;
        });

        $this->assertStringContainsString('<a name="introduction"></a>', $modifiedHtml);
        $this->assertStringContainsString('<h2>Введение</h2>', $modifiedHtml);
        $this->assertStringContainsString('Blade - это простой, но мощный движок шаблонов.', $modifiedHtml);
    }

    public function testItKeepsNavigationOnlyDocuments(): void
    {
        $modifier = new RemoveTableOfContentsModifier;

        $html = <<<'HTML'
            <ul>
                <li><h2>Пролог</h2>
                    <ul>
                        <li><a href="/docs/13.x/releases">Примечания к релизу</a></li>
                        <li><a href="/docs/13.x/upgrade">Руководство по обновлению</a></li>
                    </ul>
                </li>
                <li><h2>Начало работы</h2>
                    <ul>
                        <li><a href="/docs/13.x/installation">Установка</a></li>
                    </ul>
                </li>
            </ul>
        HTML;

        $modifiedHtml = $modifier->handle($html, function ($content) {
            return $content;
        });

        $this->assertStringContainsString('<h2>Пролог</h2>', $modifiedHtml);
        $this->assertStringContainsString('<a href="/docs/13.x/releases">Примечания к релизу</a>', $modifiedHtml);
        $this->assertStringContainsString('<a href="/docs/13.x/installation">Установка</a>', $modifiedHtml);
    }
}
