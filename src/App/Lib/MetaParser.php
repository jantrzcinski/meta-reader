<?php

declare(strict_types=1);

namespace App\Lib;

use App\Lib\Config;

class MetaParser
{
    private $meta;

    public function __construct(public string $html)
    {
    }

    public function parse()
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($this->html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        //$description = $xpath->query('/html/head/meta[@name="description"]/@content')->item(0)?->textContent;
        $nodes = $xpath->query('//head/meta');

        foreach ($nodes as $node) {
            if (preg_match('#description#si', $node->getAttribute('name'))) {
                $description = $node->getAttribute('content');
            }
        }
        // $tags = get_meta_tags('http://interia.pl');
        //var_dump($tags);

        if (!empty($description)) {
            $meta['description'] = strip_tags($description);
        } else {
            $meta['description'] = '';
        }

        $title = $xpath->query('//title')->item(0)->textContent;
        $meta['title'] = $title ?? $this->parseTitle();
        $this->meta = $meta;

        return $meta;
    }

    private function parseTitle(): string
    {

        preg_match("/<title.*>(.*)<\/title>/siU", $this->html, $titleMatches);
        return $titleMatches[1] ? strip_tags($titleMatches[1]) : '';
    }

    public function titleWidth()
    {
        $font = Config::get('FONT_TITLE');
        return $this->calculateWidth($font, $this->meta['title']);
    }

    public function descriptionWidth()
    {
        $font = Config::get('FONT_DESCRIPTION');
        return $this->calculateWidth($font, $this->meta['description']);
    }

    private function calculateWidth(array $font, string $text): int
    {
        $path = Config::get('FONT_PATH');
        list($left,, $right) = imagettfbbox($font[1], 0, $path . $font[3], $text);
        $width = $right - $left;
        //var_dump(imagettfbbox($font[1], 0, $path . $font[3], $text));
        return $width;
    }


    public function getCalculatedData(): array
    {
        $data = [];
        $data['title'] = [
            'text' => $this->meta['title'],
            'titleLength' => mb_strlen($this->meta['title']),
            'titleWidth' =>  $this->titleWidth(),
        ];

        $data['description'] = [
            'text' => $this->meta['description'],
            'descriptionLength' => mb_strlen($this->meta['description']),
            'descriptionWidth' => $this->descriptionWidth(),
        ];

        return $data;
    }
}
