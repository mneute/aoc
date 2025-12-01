<?php

declare(strict_types=1);

namespace App\Year2025\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    private const int MAX = 100;

    public function run(): Result
    {
        $part1 = $part2 = 0;
        $dial = 50;

        foreach ($this->readFile() as $line) {
            preg_match('#^([LR])(\d+)$#', $line, $matches);
            \assert(\array_key_exists(1, $matches) && \array_key_exists(2, $matches));

            $clicks = (int) $matches[2];
            $part2 += (int) ($clicks / self::MAX);
            $clicks %= self::MAX;

            if ('L' === $matches[1]) {
                if ($clicks >= $dial && 0 !== $dial) ++$part2;
                $dial += (self::MAX - $clicks);
            } else {
                $dial += $clicks;
                if ($dial >= self::MAX) ++$part2;
            }
            $dial %= self::MAX;

            if (0 === $dial) ++$part1;
        }

        return new Result($part1, $part2);
    }
}
