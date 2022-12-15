<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day15
{
    use ContentReader;

    private const PART2_MAX = 4_000_000;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        [$beacons, $sensors, $inputs] = $this->parseLines($lines);

        return (string) count(array_unique($this->discoverAtY($inputs, 2_000_000, $beacons)));
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        [, , $inputs] = $this->parseLines($lines);

        for ($y = 0; $y < self::PART2_MAX; $y++) {
            $missingX = $this->findMissingX($inputs, $y);
            if (!\is_null($missingX)) {
                return (string) ($missingX * self::PART2_MAX + $y);
            }
        }

        throw new \Exception('Unable to solve)');
    }

    public function discoverAtY(array $inputs, int $interestedY, array $beacons): array
    {
        $noBeaconsPossible = [];
        foreach ($inputs as $value) {
            $manhattan = $value['sensor']['range'];
            $reduction = abs($value['sensor']['y'] - $interestedY);
            $remainingDistance = $manhattan - $reduction;
            if ($remainingDistance < 0) {
                continue;
            }
            for ($x = $value['sensor']['x'] - $remainingDistance; $x <= $value['sensor']['x'] + $remainingDistance; $x++) {
                $key = $x . ',' . $interestedY;
                if (!in_array($key, $beacons)) {
                    $noBeaconsPossible[] = $key;
                }
            }
        }
        return $noBeaconsPossible;
    }

    public function findMissingX(array $inputs, int $interestedY): ?int
    {
        $excludedRanges = [];
        foreach ($inputs as $value) {
            $manhattan = $value['sensor']['range'];
            $reduction = abs($value['sensor']['y'] - $interestedY);
            $remainingDistance = $manhattan - $reduction;
            if ($remainingDistance < 0) {
                continue;
            }

            $sensorFarLeft = max(0,$value['sensor']['x'] - $remainingDistance);
            $sensorFarRight = min($value['sensor']['x'] + $remainingDistance, self::PART2_MAX);
            $range = [$sensorFarLeft, $sensorFarRight];
            if ($sensorFarLeft === 0 && $sensorFarRight === self::PART2_MAX) {
                return null;
            }
            $excludedRanges[$sensorFarLeft] = $range;
        }

        ksort($excludedRanges);

        $max = $excludedRanges[0][1];
        foreach ($excludedRanges  as $excludedRange) {
            if ($excludedRange[0] <= $max) {
                $max = max($max, $excludedRange[1]);
                if ($max === self::PART2_MAX) {
                    return null;
                }
            }
        }

        return $max + 1;
    }

    public function parseLines(array $lines): array
    {
        $beacons = [];
        $sensors = [];
        $inputs = array_map(function (string $line) use (&$sensors, &$beacons) {
            $line = str_replace('=', ' ', $line);
            $parts = explode(' ', $line);

            $sensors[] = $parts[3] . (int)$parts[5];
            $beacons[] = $parts[11] . (int)$parts[13];
            return [
                'sensor' => [
                    'x' => (int)$parts[3],
                    'y' => (int)$parts[5],
                    'range' => abs((int)$parts[5] - (int)$parts[13]) + abs((int)$parts[3] - (int)$parts[11]),
                ],
                'beacon' => [
                    'x' => (int)$parts[11],
                    'y' => (int)$parts[13],
                ]
            ];
        }, $lines);

        return [$beacons, $sensors, $inputs];
    }
}