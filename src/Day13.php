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
            if ($this->packetDataIsInOrder($pairs[$i][0], $pairs[$i][1])) {
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

        usort($packets, fn ($left, $right) => !$this->packetDataIsInOrder($left, $right) ? 1 : -1);

        $packetCount = count($packets);
        $divider = 1;
        for($i = 0; $i < $packetCount; $i++) {
            $packetAsString = json_encode($packets[$i]);

            if ($packetAsString === '[[2]]' || $packetAsString === '[[6]]') {
                $divider *= $i +1;
            }
        }

        return (string) $divider;
    }

    private function packetDataIsInOrder(int|array $left, int|array $right): ?bool
    {
        static $nesting = 0;
//        printf("%sCompare %s vs %s\r\n", str_repeat('  ', $nesting), json_encode($left), json_encode($right));
        $nesting++;
        if (is_int($left) && is_int($right)) {

            if ($left === $right) {
                return null;
            }
            return $left < $right;
        }

        if (is_int($left) || is_int($right)) {
            return $this->packetDataIsInOrder((array) $left, (array) $right);
        }

        $countLeft = count($left);
        $countRight = count($right);
        $maxCount = max($countLeft, $countRight);

        for ($x = 0; $x < $maxCount; $x++) {
            if (!array_key_exists($x, $left)) {
                return true;
            }
            if (!array_key_exists($x, $right)) {
                return false;
            }
            $result = $this->packetDataIsInOrder($left[$x], $right[$x]);
            if (!\is_null($result)) {
                return $result;
            }
        }

        return null;
    }
}