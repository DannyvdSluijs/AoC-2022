<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day03
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsLines();
        $priorities = array_map(function (string $input) {
            $length = strlen($input) / 2;
            $intersect = array_intersect(
                str_split(substr($input, 0, $length)),
                str_split(substr($input, $length)),
            );
            $duplicate = array_pop($intersect);

            $ord = ord($duplicate);
            return $ord - ($ord >= 97 ? 96 : 38);
        }, $content);

        return (string) array_sum($priorities);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLines();
        $groups = array_chunk($content, 3);

        $priorities = array_map(function($group) {
            $items = array_map(str_split(...), $group);
            $intersect = array_intersect($items[0], $items[1], $items[2]);

            $duplicate = array_pop($intersect);
            $ord = ord($duplicate);
            return $ord - ($ord >= 97 ? 96 : 38);

        }, $groups);

        return (string) array_sum($priorities);
    }
}