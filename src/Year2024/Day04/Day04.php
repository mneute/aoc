<?php

declare(strict_types=1);

namespace App\Year2024\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    private const array DIRECTIONS = [
        'N' => [-1, 0],
        'NE' => [-1, 1],
        'E' => [0, 1],
        'SE' => [1, 1],
        'S' => [1, 0],
        'SW' => [1, -1],
        'W' => [0, -1],
        'NW' => [-1, -1],
    ];

    /** @var list<list<string>> */
    private static array $input;
    private static int $maxI;
    private static int $maxJ;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            self::$input[] = str_split($line);
        }
        self::$maxI = \count(self::$input);
        self::$maxJ = \count(self::$input[0]);

        foreach (self::$input as $i => $line) {
            foreach ($line as $j => $char) {
                if ('X' === $char) {
                    $pt1 += $this->tryPart1($i, $j);
                }
            }
        }

        return new Result($pt1, $pt2);
    }

    private function tryPart1(int $i, int $j): int
    {
        $steps = 3;
        $count = 0;

        foreach (self::DIRECTIONS as $direction) {
            // bounds check
            $end = [
                $i + ($direction[0] * $steps),
                $j + ($direction[1] * $steps),
            ];

            if ($end[0] < 0 || $end[0] >= self::$maxI || $end[1] < 0 || $end[1] >= self::$maxJ) continue;

            if ($this->tryDirectionPart1($i, $j, $direction)) ++$count;
        }

        return $count;
    }

    /**
     * @param array{0: int, 1: int} $steps
     */
    private function tryDirectionPart1(int $i, int $j, array $steps): bool
    {
        $i += $steps[0];
        $j += $steps[1];

        foreach (['M', 'A', 'S'] as $letter) {
            if ($letter !== self::$input[$i][$j]) return false;

            $i += $steps[0];
            $j += $steps[1];
        }

        return true;
    }
}
