<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day14
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $state = $this->parseInput($lines);
        $grid = $this->fillGrid($state);
        $moves = [[0, +1], [-1, +1], [+1, +1]];
        $sandCount = 0;
        while (true) {
            $sand = $state['sourceOfSand'];
            while (true) {
                foreach ($moves as $move) {
                    [$xOffset, $yOffset] = $move;
                    if ($grid[$sand['y'] + $yOffset][$sand['x'] + $xOffset] === '.') {
                        $sand['x'] += $xOffset;
                        $sand['y'] += $yOffset;
                        if ($sand['x'] < $state['minX'] || $sand['x'] > $state['maxX'] || $sand['y'] > $state['maxY'] ) {
                            break 3;
                        }
                        continue 2;
                    }
                }

                $grid[$sand['y']][$sand['x']] = 'o';
                $sandCount++;
                break;
            }
        }

        return (string) ($sandCount);
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $state = $this->parseInput($lines);
        $state['lines'][] = [
            'start' => ['x' => 0, 'y' => $state['maxY'] + 2],
            'end' => ['x' => 10000, 'y' => $state['maxY'] + 2],
            'isHorizontal' => true,
        ];
        $grid = $this->fillGrid($state);
        $moves = [[0, +1], [-1, +1], [+1, +1]];

        $sandCount = 0;
        while (true) {
            $sand = $state['sourceOfSand'];
            while (true) {
                foreach ($moves as $move) {
                    [$xOffset, $yOffset] = $move;
                    if ($grid[$sand['y'] + $yOffset][$sand['x'] + $xOffset] === '.') {
                        $sand['x'] += $xOffset;
                        $sand['y'] += $yOffset;
                        continue 2;
                    }
                }

                $grid[$sand['y']][$sand['x']] = 'o';
                $sandCount++;
                if ($sand === ['x' => 500, 'y' => 0]) {
                    break 2;
                }

                break;
            }
        }

        return (string) ($sandCount);
    }

    private function parseInput(array $input): array
    {
        $lines = [];
        $minX = PHP_INT_MAX;
        $maxX = 0;
        $minY = PHP_INT_MAX;
        $maxY = 0;

        foreach ($input as $line) {
            $parts = explode(' -> ', $line);
            $partCount = count($parts);
            for ($x = 0; $x < $partCount - 1; $x++) {
                $start = explode(',', $parts[$x]);
                [$startX, $startY] = array_map(intval(...), $start);
                $end = explode(',', $parts[$x + 1]);
                [$endX, $endY]  = array_map(intval(...), $end);
                $lines[] = [
                    'start' => ['x' => $startX,  'y' => $startY],
                    'end' => ['x' => $endX,  'y' => $endY],
                    'isHorizontal' => $startY === $endY,
                ];

                $minX = min($minX, $startX, $endX);
                $maxX = max($maxX, $startX, $endX);
                $minY = min($minY, $startY, $endY);
                $maxY = max($maxY, $startY, $endY);
            }
        }

        return [
            'lines' => $lines,
            'minX' => $minX,
            'maxX' => $maxX,
            'minY' => $minY,
            'maxY' => $maxY,
            'sourceOfSand' => ['x' => 500, 'y' => 0]
        ];
    }

    public function fillGrid(array $state): array
    {
        $grid = [];

        for ($y = 0; $y <= $state['maxY'] + 1; $y++) {
            $grid[$y] = array_fill(325, 350, '.');
        }

        $sourceOfSand = $state['sourceOfSand'];
        $grid[$sourceOfSand['y']][$sourceOfSand['x']] = '+';

        foreach ($state['lines'] as $line) {
            if ($line['isHorizontal']) {
                $y = $line['start']['y'];
                $min = min($line['start']['x'], $line['end']['x']);
                $max = max($line['start']['x'], $line['end']['x']);
                for ($x = $min; $x <= $max; $x++) {
                    $grid[$y][$x] = '#';
                }
            }
            if (!$line['isHorizontal']) {
                $x = $line['start']['x'];

                $min = min($line['start']['y'], $line['end']['y']);
                $max = max($line['start']['y'], $line['end']['y']);
                for ($y = $min; $y <= $max; $y++) {
                    $grid[$y][$x] = '#';
                }
            }
        }
        return $grid;
    }
}