<?php

declare(strict_types=1);

namespace App\Year2024\Day11;

use App\AbstractPuzzle;
use App\Result;

final class Day11 extends AbstractPuzzle
{
    private array $cache = [];

    public function run(): Result
    {
        $stones = array_map(intval(...), explode(' ', trim(file_get_contents($this->getFilePath()))));

        $pt1 = $pt2 = 0;

        foreach ($stones as $stone) {
            $pt1 += $this->blink($stone, 25);
            $pt2 += $this->blink($stone, 75);
        }

        return new Result($pt1, $pt2);
    }

    /**
     * @param int<0, max> $stone
     * @param int<0, max> $blinks
     *
     * @return int<0, max> how many stones would you get if you transformed \$stone \$blinks times
     */
    private function blink(int $stone, int $blinks): int
    {
        if (0 === $blinks) return 1;

        $key = "$stone-$blinks";
        if (isset($this->cache[$key])) return $this->cache[$key];

        if (0 === $stone) {
            $count = $this->blink(1, $blinks - 1);
        } elseif (0 === \strlen((string) $stone) % 2) {
            [$left, $right] = str_split((string) $stone, \strlen((string) $stone) / 2);
            $count = $this->blink((int) $left, $blinks - 1) + $this->blink((int) $right, $blinks - 1);
        } else {
            $count = $this->blink($stone * 2024, $blinks - 1);
        }

        return $this->cache[$key] = $count;
    }
}
