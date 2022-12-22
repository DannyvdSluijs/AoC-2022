<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day21
{
    use ContentReader;

    public function partOne(): string
    {
        $monkeys = $this->parseInput();

        return (string) $this->solve($monkeys['root'], $monkeys);;
    }

    public function partTwo(): string
    {
        $monkeys = $this->parseInput();
        $root = $monkeys['root'];
        $left = $monkeys[$root['left']];
        $right = $monkeys[$root['right']];

        $humanInLeft = $this->hasChild($left, 'humn', $monkeys);

        $target = $this->solve($humanInLeft ? $right : $left, $monkeys);
        $answer = $this->reduce($target, $humanInLeft ? $left : $right, $monkeys);

        return (string) $answer;
    }

    private function solve(array $monkey, array $monkeys): int
    {
        $monkey = $monkeys[$monkey['name']];
        if (is_null($monkey['operator'])) {
            return $monkey['left'];
        }

        $left = $monkeys[$monkey['left']];
        $leftValue = $this->solve($left, $monkeys);
        $right = $monkeys[$monkey['right']];
        $rightValue = $this->solve($right, $monkeys);

        return match ($monkey['operator']) {
            '+' => $leftValue + $rightValue,
            '-' => $leftValue - $rightValue,
            '*' => $leftValue * $rightValue,
            '/' => $leftValue / $rightValue,
        };
    }

    private function parseInput(): array
    {
        $content = $this->readInputAsLines();
        $monkeys = [];
        foreach ($content as $line) {
            $parts = explode(' ', $line);
            $partsCount = count($parts);
            $name = substr($parts[0], 0, 4);

            $monkeys[$name] = [
                'name' => $name,
                'left' => $partsCount === 2 ? (int)$parts[1] : $parts[1],
                'operator' => $parts[2] ?? null,
                'right' => $parts[3] ?? null,
            ];
        }
        return $monkeys;
    }

    private function hasChild(array $parentMonkey, string $child, array $monkeys): bool
    {
        if (is_null($parentMonkey['operator'])) {
            return false;
        }

        if ($parentMonkey['left'] === $child || $parentMonkey['right'] === $child) {
            return true;
        }

        return $this->hasChild($monkeys[$parentMonkey['left']], $child, $monkeys)
            || $this->hasChild($monkeys[$parentMonkey['right']], $child, $monkeys);
    }

    private function reduce(int $target, array $monkey, array $monkeys): int
    {
        if (is_null($monkey['operator'])) {
            return $monkey['left'];
        }

        $left = $monkeys[$monkey['left']];
        $right = $monkeys[$monkey['right']];
        $humanInLeft = $this->hasChild($left, 'humn', $monkeys);

        if ($monkey['left'] === 'humn') {
            $value = $this->solve($right, $monkeys);
            return match ($monkey['operator']) {
                '+' => $target - $value,
                '-' => $target + $value,
                '*' => $target / $value,
                '/' => $humanInLeft ? $target * $value : $target / $value,
            };
        }
        if ($monkey['right'] === 'humn') {
            $value = $this->solve($left, $monkeys);
            return match ($monkey['operator']) {
                '+' => $target - $value,
                '-' => $value - $target,
                '*' => $target / $value,
                '/' => $humanInLeft ? $target * $value : $target / $value,
            };
        }

        if ($humanInLeft) {
            $unknown = $left;
            $value = $this->solve($right, $monkeys);
        } else {
            $unknown = $right;
            $value = $this->solve($left, $monkeys);
        }

        $newTarget = match ($monkey['operator']) {
            '+' => $target - $value,
            '-' => $humanInLeft ? $target + $value : $value - $target,
            '*' => $target / $value,
            '/' => $humanInLeft ? $target * $value : $target / $value,
        };
        
        return $this->reduce($newTarget, $unknown, $monkeys);
    }
}