<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day02
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();

        $sum = 0;
        foreach ($lines as $line) {
            $sum += match($line) {
                'A X' => 1 + 3,
                'A Y' => 2 + 6,
                'A Z' => 3 + 0,
                'B X' => 1 + 0,
                'B Y' => 2 + 3,
                'B Z' => 3 + 6,
                'C X' => 1 + 6,
                'C Y' => 2 + 0,
                'C Z' => 3 + 3,
            };
        }

        return (string) $sum;
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();

        $sum = 0;
        foreach ($lines as $line) {
            $sum += match($line) {
                'A X' => 3 + 0,
                'A Y' => 1 + 3,
                'A Z' => 2 + 6,
                'B X' => 1 + 0,
                'B Y' => 2 + 3,
                'B Z' => 3 + 6,
                'C X' => 2 + 0,
                'C Y' => 3 + 3,
                'C Z' => 1 + 6,
            };
        }

        return (string) $sum;
    }
}