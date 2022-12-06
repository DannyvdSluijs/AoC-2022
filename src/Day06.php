<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day06
{
    use ContentReader;

    public function partOne(): string
    {
        return (string) $this->solve(4);
    }

    public function partTwo(): string
    {
        return (string) $this->solve(14);
    }

    private function solve(int $uniqueLength): int
    {
        $content = $this->readInputAsCharacters();
        $length = count($content);

        for ($x = $uniqueLength; $x < $length; $x++) {
            $values = array_slice($content, $x - ($uniqueLength - 1), $uniqueLength);
            if (count(array_unique($values)) === $uniqueLength) {
                break;
            }
        }

        return $x + 1;
    }
}