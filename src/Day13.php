<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day13
{
    use ContentReader;
    public function partOne(): string
    {
        $content = $this->readInput();
        $chunks = explode("\n\n", $content);

        $pairs = array_map(function ($chunk) {
            [$left, $right] = explode("\n", $chunk);

            return [json_decode($left), json_decode($right)];
        }, $chunks);

        $pairsCount = count($pairs);
        $sum = 0;
        for ($i = 0; $i < $pairsCount; $i++) {
            if ($this->packetDataIsInOrder($pairs[$i][0], $pairs[$i][1]) === -1) {
                $sum += $i + 1;
            }
        }

        return (string) $sum;
    }

    public function partTwo(): string
    {
        $content = $this->readInput();
        $content = str_replace("\n\n", "\n", $content);
        $content .= "\n[[2]]\n[[6]]";

        $packets = explode("\n", $content);
        $packets = array_map(fn($line) => json_decode($line), $packets);

        usort($packets, fn ($left, $right) => $this->packetDataIsInOrder($left, $right));

        return (string) ((array_search([[2]], $packets) + 1) * (array_search([[6]], $packets) + 1));
    }

    private function packetDataIsInOrder(int|array $left, int|array $right): int
    {
        if (is_int($left) && is_int($right)) {
            return $left <=> $right;
        }
        if (is_int($left) || is_int($right)) {
            return $this->packetDataIsInOrder((array) $left, (array) $right);
        }

        $maxCount = max(count($left), count($right));

        for ($x = 0; $x < $maxCount; $x++) {
            if (!array_key_exists($x, $left)) {
                return -1;
            }
            if (!array_key_exists($x, $right)) {
                return 1;
            }
            $result = $this->packetDataIsInOrder($left[$x], $right[$x]);
            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }
}