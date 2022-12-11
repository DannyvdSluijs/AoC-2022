<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day11
{
    use ContentReader;

    public function partOne(): string
    {
        $state = $this->parseInput();
        return (string) $this->solve($state, 20);
    }

    public function partTwo(): string
    {
        $state = $this->parseInput();
        return (string) $this->solve($state, 10_000, false);
    }

    private function parseInput(): array
    {
        $content = $this->readInput();
        $monkeys = explode("\n\n", $content);

        $state = ['monkeys' => [], 'reducer' => 1];
        foreach ($monkeys as $m) {
            $lines = explode("\n", $m);
            [, $items] = explode(':', $lines[1]);
            $operator = str_contains($lines[2], '*') ? '*' : '+';
            $operationChunks = explode(' ', $lines[2]);
            $operationValue = array_pop($operationChunks);
            $operation = fn() => throw new \RuntimeException('Not implemented');
            if ($operationValue === 'old' && $operator === '*') {
                $operation = fn(int $old) => $old * $old;
            }
            if ($operationValue !== 'old' && $operator === '+') {
                $operation = fn(int $old) => $old + (int) $operationValue;
            }
            if ($operationValue !== 'old' && $operator === '*') {
                $operation = fn(int $old) => $old * (int) $operationValue;
            }
            $testChunks = explode(' ', $lines[3]);
            $testValue = (int) array_pop($testChunks);
            $test = fn($worryLevel) => $worryLevel % $testValue === 0;
            $state['reducer'] *= $testValue;

            $monkey = [
                'number' => (int) substr($lines[0], -2, 1),
                'items' => array_map(intval(...), explode(',', $items)),
                'operation' => $operation,
                'test' => $test,
                'true' => (int) substr($lines[4], -1, 1),
                'false' => (int) substr($lines[5], -1, 1),
                'inspectionCount' => 0
            ];
            $state['monkeys'][$monkey['number']] = $monkey;
        }

        return $state;
    }

    private function solve(array $state, int $maxRounds, bool $isPartOne = true): int
    {
        for ($rounds = 1; $rounds <= $maxRounds; $rounds++) {
            foreach ($state['monkeys'] as $monkey) {
                $numberItems = count($state['monkeys'][$monkey['number']]['items']);
                for ($i = 0; $i < $numberItems; $i++) {
                    $item = array_shift($state['monkeys'][$monkey['number']]['items']);
                    $new = $monkey['operation']($item);
                    $bored = $isPartOne ? intdiv($new, 3) : ($new % $state['reducer']);
                    $test = $monkey['test']($bored);
                    $target = $test ? $monkey['true'] : $monkey['false'];
                    $state['monkeys'][$monkey['number']]['inspectionCount']++;
                    $state['monkeys'][$target]['items'][] = $bored;
                }
            }
        }

        $counts = array_map(fn(array $monkey) => $monkey['inspectionCount'], $state['monkeys']);
        sort($counts);
        [$first, $second] = array_slice($counts, -2);

        return $first * $second;
    }
}