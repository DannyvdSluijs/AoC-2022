<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day04
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsLines();
        $content = array_map(function($c) {
            $c = str_replace(['-'], ',', $c);
            return array_map(intval(...), explode(',', $c));

        }, $content);
        $totalWithOverlap = 0;

        foreach ($content as $numbers) {

            if ($numbers[0] <= $numbers[2] && $numbers[1] >= $numbers[3]) {
                $totalWithOverlap++;
                continue;
            }
            if ($numbers[2] <= $numbers[0] && $numbers[3] >= $numbers[1]) {
                $totalWithOverlap++;
                continue;
            }
        }

        return (string) $totalWithOverlap;
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLines();
        $content = array_map(function($c) {
            $c = str_replace(['-'], ',', $c);
            return array_map(intval(...), explode(',', $c));

        }, $content);
        $totalWithOverlap = 0;

        foreach ($content as $numbers) {
            if ($numbers[0] >= $numbers[2] && $numbers[0] <= $numbers[3]) {
                $totalWithOverlap++;
                continue;
            }
            if ($numbers[1] >= $numbers[2] && $numbers[1] <= $numbers[3]) {
                $totalWithOverlap++;
                continue;
            }
            if ($numbers[2] >= $numbers[0] && $numbers[2] <= $numbers[1]) {
                $totalWithOverlap++;
                continue;
            }
            if ($numbers[3] >= $numbers[0] && $numbers[3] <= $numbers[1]) {
                $totalWithOverlap++;
                continue;
            }
        }

        return (string) $totalWithOverlap;
    }
}