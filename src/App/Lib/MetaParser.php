<?php

declare(strict_types=1);

namespace App\Lib;

use App\Lib\Config;

class MetaParser
{
    private ?array $meta = null;

    public function __construct(public string $html)
    {
    }

    public function parse()
    {
        $doc = $this->createDocument();
        $xpath = $this->createXPath($doc);
        $this->parseDescription($xpath);
        $this->parseTitle($xpath);

        return $this->meta;
    }

    private function createDocument(): \DOMDocument
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($this->html);
        libxml_clear_errors();

        return $doc;
    }

    private function createXPath(\DOMDocument $doc): \DOMXPath
    {
        return new \DOMXPath($doc);
    }

    private function parseDescription(\DOMXPath $xpath): void
    {
        $nodes = $xpath->query('//head/meta');
        $description = '';

        foreach ($nodes as $node) {
            if (preg_match('#description#si', (string) $node->getAttribute('name'))) {
                $description = $node->getAttribute('content');
                break;
            }
        }

        $this->meta['description'] = empty($description) ? '' : trim(strip_tags((string) $description));
    }

    private function parseTitle(\DOMXPath $xpath): void
    {
        $titleNode = $xpath->query('//title')->item(0);
        $title = $titleNode?->textContent ?? $this->parseTitleFromHtml();

        $this->meta['title'] = trim($title);
    }

    private function parseTitleFromHtml(): string
    {
        preg_match("/<title.*>(.*)<\/title>/siU", $this->html, $titleMatches);
        return empty($titleMatches[1]) ? '' : strip_tags($titleMatches[1]);
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
