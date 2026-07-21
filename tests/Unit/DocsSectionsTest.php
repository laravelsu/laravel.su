<?php

namespace Tests\Unit;

use App\Docs;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocsSectionsTest extends TestCase
{
    public function testSearchSectionsExcludeTableOfContentsAndAnchorMarkers(): void
    {
        Storage::fake('docs');
        Storage::disk('docs')->put('13.x/search-test.md', <<<'MARKDOWN'
            ---
            title: Search test
            ---

            # Search test

            - [Введение](#introduction)
            - [Установка](#installation)

            <a name="introduction"></a>
            ## Введение

            Основное содержимое раздела.

            <a name="installation"></a>
            ## Установка

            Инструкция по установке.
            MARKDOWN);

        $sections = (new Docs('13.x', 'search-test'))->getSections();
        $indexedContent = $sections->pluck('content')->implode(' ');

        $this->assertStringNotContainsString('href="#introduction"', $indexedContent);
        $this->assertStringNotContainsString('name="introduction"', $indexedContent);
        $this->assertStringContainsString('Основное содержимое раздела.', $indexedContent);
        $this->assertStringContainsString('Инструкция по установке.', $indexedContent);
    }
}
