<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day25
{
    use ContentReader;

    public function partOne(): string
    {
        $snafuNumbers = $this->readInputAsLines();
        $decimalNumbers = array_map($this->snafuToDec(...), $snafuNumbers);
        return $this->decToSnafu(array_sum($decimalNumbers));
    }

    public function partTwo(): string
    {
        return 'No part two on day 25';
    }

    private function snafuToDec(string $snafu): int
    {
        $length = strlen($snafu);
        $total = 0;

        for ($x = $length - 1; $x >= 0; $x--) {
            $value = $snafu[$x];
            $power = 5 ** ($length - $x -1);
            $total += match ($value) {
                '0', '1', '2' => $value * $power,
                '-' => -1 * $power,
                '=' => -2 * $power,
            };
        }

        return $total;
    }

    private function decToSnafu(int $number): string
    {
        $result = '';
        for ($x = 25; $x >= 0; $x--) {
            $divider = 5 ** $x;
            $divisionResult = $number / $divider;

            if ($divisionResult > 0 && $divisionResult - floor($divisionResult) >= 0.5) {
                $divisionResult = ceil($divisionResult);
            }
            if ($divisionResult < 0 && $divisionResult - ceil($divisionResult) <= -0.5) {
                $divisionResult = floor($divisionResult);
            }

            $addition = (int) min($divisionResult, 2);

            $char = match ($addition) {
                0, 1, 2 => (string) $addition,
                -1 => '-',
                -2 => '=',
            };
            if ($result === '' && $char === '0') {
                continue;
            }
            $result .= $char;

            $number -= $addition * $divider;
        }

        return $result;
    }
}