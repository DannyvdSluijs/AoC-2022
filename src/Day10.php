<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day10
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsWords();
        $instructions = array_map(fn($words) => [$words[0], (int) ($words[1] ?? 0)], $content);
        $x = 1;
        $sum = 0;
        $cycle = 0;

        while (true) {
            $cycle++;

            if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
                $sum += $cycle * $x;
            }

            $instruction = array_shift($instructions);
            switch ($instruction[0]) {
                case 'noop':
                    break;
                case 'addx':
                    $cycle++;
                    if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
                        $sum += $cycle * $x;

                    }


                    $x += $instruction[1];
                    break;
            }

            if ($cycle > 221) {
                break;
            }
        }

        return (string) $sum;
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsWords();
        $instructions = array_map(fn($words) => [$words[0], (int) ($words[1] ?? 0)], $content);
        $spritePosition = 1;
        $cycle = 0;
        $crt = [];

        while (true) {
            $cycle++;
            if (in_array(($cycle - 1) % 40, [$spritePosition -1, $spritePosition, $spritePosition +1])) {
                $crt[] = $cycle - 1;
            }

            $instruction = array_shift($instructions);
            if (!is_null($instruction)) {
                switch ($instruction[0]) {
                    case 'noop':
                        break;
                    case 'addx':
                        $cycle++;
                        if (in_array(($cycle - 1) % 40, [$spritePosition - 1, $spritePosition, $spritePosition + 1])) {
                            $crt[] = $cycle - 1;
                        }
                        $spritePosition += $instruction[1];
                        break;
                }
            }

            if ($cycle >= 240) {
                break;
            }
        }

        for($y = 0; $y < 6; $y++) {
            for($x = 0; $x < 40; $x++) {
                echo (in_array($x + ($y * 40), $crt) ? '#' : ' ');
            }
            echo PHP_EOL;
        }

        return 'See above';
    }
}