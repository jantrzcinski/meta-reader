<?php

declare(strict_types=1);

namespace App\Lib;

use App\Lib\Config;

class MetaAnalyzer
{
    public function __construct(public array $meta)
    {
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
        return $width;
    }

    public function getCalculatedData(): array
    {
        $title = $this->meta['title'];
        $description = $this->meta['description'];

        return [
            'title' => [
                'text' => $title,
                'length' => mb_strlen($title),
                'width' => $this->titleWidth(),
            ],
            'description' => [
                'text' => $description,
                'length' => mb_strlen($description),
                'width' => $this->descriptionWidth(),
            ],
        ];
    }
}
