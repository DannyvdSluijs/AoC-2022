<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2022;

use Dannyvdsluijs\AdventOfCode2022\Concerns\ContentReader;

class Day07
{
    use ContentReader;

    public function partOne(): string
    {
        $dirs = $this->getDirSizes();
        $matches = array_filter($dirs, fn(int $size) => $size <= 100_000);

        return (string) array_sum($matches);
    }

    public function partTwo(): string
    {
        $dirs = $this->getDirSizes();

        $totalDiskSpace = 70_000_000;
        $required = 30_000_000;
        $free = $totalDiskSpace - $dirs['/'];
        $needed = $required - $free;

        $matches = array_filter($dirs, fn(int $size) => $size >= $needed);

        return (string) min($matches);
    }

    public function getDirSizes(): array
    {
        $lines = $this->readInputAsLines();
        $pwd = [];
        $dirs = ['/' => 0];
        $files = [];

        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            switch ($parts[0]) {
                case '$':
                    switch ($parts[1]) {
                        case 'cd':
                            if ($parts[2] === '/') {
                                break;
                            }
                            if ($parts[2] === '..') {
                                array_pop($pwd);
                                break;
                            }
                            $pwd[] = $parts[2];
                            break;
                        case 'ls':
                            break;
                    }
                    break;
                default:
                    if ($parts[0] !== 'dir') {
                        $filename = implode('/', $pwd) . '/' . $parts[1];
                        $size = (int)$parts[0];
                        $files[$filename] = $size;

                        $copy = $pwd;
                        while ($copy !== []) {
                            $dir = '/' . implode('/', $copy);
                            $dirs[$dir] += $size;
                            array_pop($copy);
                        }
                    }
                    if ($parts[0] === 'dir') {
                        $dir = '/' . $parts[1];
                        if (count($pwd)) {
                            $dir = '/' . implode('/', $pwd) . $dir;
                        }
                        $dirs[$dir] = 0;
                    }
                    break;
            }
        }

        $dirs['/'] = array_sum($files);

        return $dirs;
    }
}