<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day12
{
    use ContentReader;

    public function partOne(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        $numberOfRows = count($grid);
        $numberOfColumns = count($grid[0]);

        for ($y = 0; $y <$numberOfRows; $y++) {
            for ($x = 0; $x < $numberOfColumns; $x++) {
                if ($grid[$y][$x] === 'S') {
                    $start = [
                        'x' => $x,
                        'y' => $y,
                        'elevation' => ord('a'),
                    ];
                }
                if ($grid[$y][$x] === 'E') {
                    $target = [
                        'x' => $x,
                        'y' => $y,
                        'elevation' => 'z',
                    ];
                }
            }
        }

        if (is_null($start)) {
            throw new \Exception('Unable to find S');
        }
        if (is_null($target)) {
            throw new \Exception('Unable to find E');
        }

        $minPathLength = $this->findPathLength([
            'grid' => $grid,
            'start' => $start,
            'isInvalid' => fn($currentElevation, $nextElevation) => $nextElevation > $currentElevation + 1,
            'isFinish' => fn(array $state) => $state['x'] === $target['x'] && $state['y'] === $target['y'],
        ]);

        return (string) $minPathLength;
    }

    public function partTwo(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        $numberOfRows = count($grid);
        $numberOfColumns = count($grid[0]);

        for ($y = 0; $y <$numberOfRows; $y++) {
            for ($x = 0; $x < $numberOfColumns; $x++) {
                if ($grid[$y][$x] === 'E') {
                    $target = [
                        'x' => $x,
                        'y' => $y,
                        'elevation' => ord('z'),
                    ];
                }
            }
        }

        if (is_null($target)) {
            throw new \Exception('Unable to find E');
        }

        $minPathLength = $this->findPathLength([
            'grid' => $grid,
            'start' => $target,
            'isInvalid' => fn($currentElevation, $nextElevation) => $nextElevation < $currentElevation - 1,
            'isFinish' => fn(array $state) => $state['elevation'] === ord('a'),
        ]);

        return (string) $minPathLength;
    }

    private function findPathLength(array $state): int
    {
        $grid = $state['grid'];
        $start = $state['start'];
        $isInvalid = $state['isInvalid'];
        $isFinish = $state['isFinish'];

        $queue = new \SplPriorityQueue();
        $queue->insert([
            'elevation' =>  $start['elevation'],
            'x' => $start['x'],
            'y' => $start['y'],
            'steps' => 0,
        ],
            5000
        );
        $queued = [$start['x'] . ',' . $start['y']];
        $possibleOffsets = [['x' => -1, 'y' => 0,], ['x' => +1, 'y' => 0,], ['x' => 0, 'y' => -1,], ['x' => 0, 'y' => +1,],];

        while ($queue->valid()) {
            $state = $queue->extract();
            if ($isFinish($state)) {
                return $state['steps'];
            }

            foreach ($possibleOffsets as $offset) {
                $newX = $state['x'] + $offset['x'];
                $newY = $state['y'] + $offset['y'];
                $key = $newX . ',' . $newY;

                if (!array_key_exists($newY, $grid) || !array_key_exists($newX, $grid[$newY])) {
                    continue;
                }
                if (in_array($key, $queued)) {
                    continue;
                }

                $value = $grid[$newY][$newX];
                $value = $value !== 'S' ? $value : 'a';
                $value = $value !== 'E' ? $value : 'z';
                $next = ord($value);

                if ($isInvalid($state['elevation'], $next)) {
                    continue;
                }

                $priority = 100_000 - ($state['steps'] + 1);
                $queue->insert([
                    'elevation' => $next,
                    'x' => $newX,
                    'y' => $newY,
                    'steps' => $state['steps'] + 1,
                ], $priority);
                $queued[] = $key;
            }
        }

        return 0;
    }
}