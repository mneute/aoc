<?php

declare(strict_types=1);

namespace App\Year2024\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    private static array $input;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            self::$input[] = str_split($line);
        }

        for ($i = 0; $i < count(self::$input); $i++) {
            for ($j = 0; $j < count(self::$input[$i]); $j++) {
                if ('X' === self::$input[$i][$j]) {
                    $pt1 += $this->tryPart1($i, $j);
                }
            }
        }

        return new Result($pt1, $pt2);
    }

    private function tryPart1(int $i, int $j): int
    {
        $maxI = count(self::$input);
        $maxJ = count(self::$input[0]);
        $steps = 3;

        $directions = [
            '➡' => [0, 1],
            '↘' => [1, 1],
            '⬇' => [1, 0],
            '↙' => [1, -1],
            '⬅' => [0, -1],
            '↖' => [-1, -1],
            '⬆' => [-1, 0],
            '↗' => [-1, 1],
        ];

        $count = 0;

        foreach ($directions as $direction) {
            // bounds check
            $end = [
                $i + ($direction[0] * $steps),
                $j + ($direction[1] * $steps),
            ];

            if ($end[0] < 0 || $end[0] >= $maxI || $end[1] < 0 || $end[1] >= $maxJ) continue;

            if ($this->tryDirectionPart1($i, $direction[0], $j, $direction[1])) $count++;
        }

        return $count;
    }

    private function tryDirectionPart1(int $i, int $stepI, int $j, int $stepJ): bool
    {
        $i += $stepI;
        $j += $stepJ;

        foreach (['M', 'A', 'S'] as $letter) {
            if ($letter !== self::$input[$i][$j]) return false;

            $i += $stepI;
            $j += $stepJ;
        }

        return true;
    }
}
