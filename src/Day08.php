<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day08
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsGridOfNumbers();

        $xMax = count($content[0]);
        $yMax = count($content);

        $visibleTrees = [];
        for($x = 0; $x < $xMax; $x++) {
            $vertical = [];
            for ($i = 0; $i < $yMax; $i++) {
                $vertical[] = $content[$i][$x];
            }
            for($y = 0; $y < $yMax; $y++) {
                if ($x === 0 || $y === 0 || $x === $xMax - 1 || $y === $yMax -1) {
                    $visibleTrees[] = $x . ',' . $y;
                    continue;
                }

                $tree = $content[$y][$x];
                $horizontal = $content[$y];
                $leftOfTree = array_slice($horizontal, 0, $x);
                if (max($leftOfTree) < $tree) {
                    $visibleTrees[] = $x . ',' . $y;
                    continue;
                }

                $rightOfTree = array_slice($horizontal, $x + 1);
                if (max($rightOfTree) < $tree) {
                    $visibleTrees[] = $x . ',' . $y;
                    continue;
                }

                $northOfTree = array_slice($vertical, 0, $y);
                if (max($northOfTree) < $tree) {
                    $visibleTrees[] = $x . ',' . $y;
                    continue;
                }

                $southOfTree = array_slice($vertical, $y + 1);
                if (max($southOfTree) < $tree) {
                    $visibleTrees[] = $x . ',' . $y;
                    continue;
                }
            }
        }

        return (string) count($visibleTrees);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsGridOfNumbers();

        $xMax = count($content[0]);
        $yMax = count($content);
        $maxScenic = 0;

        for($x = 1; $x < $xMax -1; $x++) {
            $vertical = [];
            for ($i = 0; $i < $yMax; $i++) {
                $vertical[] = $content[$i][$x];
            }
            for($y = 1; $y < $yMax -1; $y++) {
                $tree = $content[$y][$x];
                $horizontal = $content[$y];

                $leftOfTree = array_reverse(array_slice($horizontal, 0, $x));
                $rightOfTree = array_slice($horizontal, $x + 1);
                $northOfTree = array_reverse(array_slice($vertical, 0, $y));
                $southOfTree = array_slice($vertical, $y + 1);

                $leftAmount = 0;
                while($leftOfTree !== []) {
                    $compare = array_shift($leftOfTree);
                    $leftAmount++;
                    if ($compare >= $tree) {
                        break;
                    }
                }

                $rightAmount = 0;
                while($rightOfTree !== []) {
                    $compare = array_shift($rightOfTree);
                    $rightAmount++;
                    if ($compare >= $tree) {
                        break;
                    }

                }

                $northAmount = 0;
                while($northOfTree !== []) {
                    $compare = array_shift($northOfTree);
                    $northAmount++;
                    if ($compare >= $tree) {
                        break;
                    }
                }

                $southAmount = 0;
                while($southOfTree !== []) {
                    $compare = array_shift($southOfTree);
                    $southAmount++;
                    if ($compare >= $tree) {
                        break;
                    }
                }

                $scenic = $leftAmount * $rightAmount * $northAmount * $southAmount;
                $maxScenic = max($maxScenic, $scenic);
            }
        }

        return (string) $maxScenic;
    }
}