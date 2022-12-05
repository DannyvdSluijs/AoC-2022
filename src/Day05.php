<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day05
{
    use ContentReader;

    public function partOne(): string
    {
        return $this->solve();
    }

    public function partTwo(): string
    {
       return $this->solve(moveMultiple: true);
    }

    private function solve($moveMultiple = false): string
    {
        $content = $this->readInput(trim: false);
        [$stateAsMultiline, $instructions] = explode("\n\n", $content);
        $instructions = explode("\n", $instructions);
        $stateAsLines = explode("\n", $stateAsMultiline);
        $index = array_pop($stateAsLines);
        $maxStackSize = count($stateAsLines);

        $length = strlen($index);
        $positions = [];
        for($x = 0; $x < $length; $x++) {
            $v = $index[$x];
            if (is_numeric($v)) {
                $positions[(int) $v] = $x;
            }
        }

        $state = [];
        for ($x = $maxStackSize - 1; $x >= 0; $x--) {
            foreach ($positions as $position => $index) {
                $state[$position] = $state[$position] ?? [];
                $value = trim($stateAsLines[$x][$index] ?? '');

                if (!empty($value)) {
                    $state[$position][] = $stateAsLines[$x][$index];

                }
            }
        }

        foreach ($instructions as $instruction) {
            [, $amount, , $from, , $to] = array_map(intval(...), explode(' ', $instruction));

            $move = !$moveMultiple ?
                array_reverse(array_slice($state[$from], -$amount)) :
                array_slice($state[$from], -$amount);
            $state[$to] = array_merge($state[$to], $move);
            $state[$from] = array_slice($state[$from], 0, -$amount);
        }

        $answer = '';
        foreach($state as $s) {
            $answer .= array_pop($s);
        }
        return $answer;
    }
}