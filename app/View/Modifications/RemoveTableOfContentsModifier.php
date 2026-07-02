<?php

namespace App\View\Modifications;

use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class RemoveTableOfContentsModifier extends HTMLModifier
{
    /**
     * @param string   $content The HTML content to be modified.
     * @param \Closure $next    The next method in the middleware pipeline.
     *
     * @return mixed The modified HTML content.
     */
    public function handle(string $content, \Closure $next)
    {
        $this->crawler($content)
            ->filter('ul')
            ->reduce(function (Crawler $node) {
                return $this->isOpeningTableOfContents($node);
            })
            ->first()
            ->each(function (Crawler $elm) use (&$content) {
                $content = Str::of($content)->replace($elm->outerHtml(), '');
            });

        return $next($content);
    }

    private function isOpeningTableOfContents(Crawler $node): bool
    {
        /** @var \DOMElement|null $list */
        $list = $node->getNode(0);

        if ($list === null) {
            return false;
        }

        $previousSibling = $list->previousSibling;

        while ($previousSibling !== null && ! $previousSibling instanceof \DOMElement) {
            $previousSibling = $previousSibling->previousSibling;
        }

        if ($previousSibling !== null) {
            return false;
        }

        $links = $node->filter('a');

        if ($links->count() === 0) {
            return false;
        }

        $linksToAnchors = $links->each(fn (Crawler $link) => Str::of((string) $link->attr('href'))->startsWith('#'));

        return $linksToAnchors === array_fill(0, $links->count(), true);
    }
}
