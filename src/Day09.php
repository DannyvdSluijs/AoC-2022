<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day09
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsWords();
        $motions = array_map(fn(array $line) => [$line[0], (int) $line[1]], $content);

        $state = [
            'head' => ['x' => 0, 'y' => 0],
            'tail' => ['x' => 0, 'y' => 0],
        ];
        $mapping = [
            'R' => ['plane' => 'x', 'step' => 1],
            'D' => ['plane' => 'y', 'step' => -1],
            'L' => ['plane' => 'x', 'step' => -1],
            'U' => ['plane' => 'y', 'step' => 1],
        ];
        $tailVisited = ['0,0'];

        foreach ($motions as [$direction, $amount]) {
            ['plane' => $plane, 'step' => $step] = $mapping[$direction];
            for($i = 0; $i < $amount; $i++) {
                $state['head'][$plane] += $step;

                $state['tail'] = $this->follow($state['head'], $state['tail']);
                $tailVisited[] = implode(',', $state['tail']);
            }
        }

        return (string) count(array_unique($tailVisited));
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsWords();
        $motions = array_map(fn(array $line) => [$line[0], (int) $line[1]], $content);

        $state = [
            0 => ['x' => 0, 'y' => 0],
            1 => ['x' => 0, 'y' => 0],
            2 => ['x' => 0, 'y' => 0],
            3 => ['x' => 0, 'y' => 0],
            4 => ['x' => 0, 'y' => 0],
            5 => ['x' => 0, 'y' => 0],
            6 => ['x' => 0, 'y' => 0],
            7 => ['x' => 0, 'y' => 0],
            8 => ['x' => 0, 'y' => 0],
            9 => ['x' => 0, 'y' => 0],
        ];
        $mapping = [
            'R' => ['plane' => 'x', 'step' => 1],
            'D' => ['plane' => 'y', 'step' => -1],
            'L' => ['plane' => 'x', 'step' => -1],
            'U' => ['plane' => 'y', 'step' => 1],
        ];
        $tailVisited = ['0,0'];

        foreach ($motions as [$direction, $amount]) {
            ['plane' => $plane, 'step' => $step] = $mapping[$direction];
            for($i = 0; $i < $amount; $i++) {
                $state[0][$plane] += $step;

                for($j = 1; $j <= 9; $j++) {
                    $state[$j] = $this->follow($state[$j - 1], $state[$j]);
                }

                $tailVisited[] = implode(',', $state[9]);
            }
        }

        return (string) count(array_unique($tailVisited));
    }

    private function follow(array $leader, array $follower): array
    {
        $xDiff = abs($leader['x'] - $follower['x']);
        $yDiff = abs($leader['y'] - $follower['y']);

        if ($xDiff <= 1 && $yDiff <= 1) {
            return $follower;
        }

        if ($yDiff === 2) {
            $follower['y'] < $leader['y'] ? $follower['y']++ : $follower['y']--;
        }
        if ($yDiff === 1) {
            $follower['y'] = $leader['y'];
        }
        if ($xDiff === 2) {
            $follower['x'] < $leader['x'] ? $follower['x']++ : $follower['x']--;
        }
        if ($xDiff === 1) {
            $follower['x'] = $leader['x'];
        }

        return $follower;
    }
}