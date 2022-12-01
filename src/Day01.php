<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day01
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsLinesOfIntegers();

        $sums = [];
        $sum = 0;
        foreach ($content as $item) {
            if ($item === 0) {
                $sums[] = $sum;
                $sum = 0;
            }

            $sum += $item;
        }

        return (string) max($sums);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLinesOfIntegers();

        $sums = [];
        $sum = 0;
        foreach ($content as $item) {
            if ($item === 0) {
                $sums[] = $sum;
                $sum = 0;
                continue;
            }

            $sum += $item;
        }
        $sums[] = $sum;
        rsort($sums);

        return (string) array_sum(array_slice($sums, 0, 3));
    }
}