<?php

declare(strict_types=1);

namespace App\Year2024\Day09;

use App\AbstractPuzzle;
use App\Result;

final class Day09 extends AbstractPuzzle
{
    private const string FREE_SPACE = '.';

    public function run(): Result
    {
        $part1 = $part2 = 0;
        $diskMap = trim(
            file_get_contents($this->getFilePath()) ?: throw new \RuntimeException(\sprintf("Unable to read file '%s'", $this->getFilePath())),
            "\r\n"
        );

        $hardDrive = [];
        $nextFreeSpace = $lastID = null;
        foreach (str_split($diskMap) as $index => $length) {
            $size = (int) $length;
            $nextFreeSpace ??= $size;

            if (0 === $index % 2) {
                $id = $index / 2;
                array_push($hardDrive, ...array_fill(0, $size, $id));
                $lastID = \count($hardDrive) - 1;
            } else {
                array_push($hardDrive, ...array_fill(0, $size, self::FREE_SPACE));
            }
        }
        \assert(\is_int($nextFreeSpace) && \is_int($lastID));

        while ($nextFreeSpace < $lastID) {
            [$hardDrive[$nextFreeSpace], $hardDrive[$lastID]] = [$hardDrive[$lastID], $hardDrive[$nextFreeSpace]];

            do {
                ++$nextFreeSpace;
            } while (($hardDrive[$nextFreeSpace] ?? self::FREE_SPACE) !== self::FREE_SPACE);
            do {
                --$lastID;
            } while (($hardDrive[$lastID] ?? '0') === self::FREE_SPACE);
        }

        $index = 0;
        while (self::FREE_SPACE !== $hardDrive[$index]) {
            $part1 += ((int) $hardDrive[$index]) * $index;
            ++$index;
        }

        return new Result($part1, $part2);
    }
}
