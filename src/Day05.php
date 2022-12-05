<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day05
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput(trim: false);
        [$stateAsMultiline, $instructions] = explode("\n\n", $content);
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

        $instructions = explode("\n", $instructions);
        foreach ($instructions as $instruction) {
            $parts = explode(' ', $instruction);
            $amount = (int) $parts[1];
            $from = (int) $parts[3];
            $to = (int) $parts[5];

            for ($x = 0; $x < $amount; $x++) {
                $v = array_pop($state[$from]);
                $state[$to][] = $v;
            }
        }

        $answer = '';
        foreach($state as $s) {
            $answer .= array_pop($s);
        }
        return $answer;
    }

    public function partTwo(): string
    {
        $content = $this->readInput(trim: false);
        [$stateAsMultiline, $instructions] = explode("\n\n", $content);
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

        $instructions = explode("\n", $instructions);
        foreach ($instructions as $instruction) {
            $parts = explode(' ', $instruction);
            $amount = (int) $parts[1];
            $from = (int) $parts[3];
            $to = (int) $parts[5];

            $temp = [];
            for ($x = 0; $x < $amount; $x++) {
                $v = array_pop($state[$from]);
                $temp[] = $v;
            }
            for ($x = 0; $x < $amount; $x++) {
                $v = array_pop($temp);
                $state[$to][] = $v;
            }

        }

        $answer = '';
        foreach($state as $s) {
            $answer .= array_pop($s);
        }
        return $answer;
    }
}