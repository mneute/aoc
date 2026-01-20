<?php

declare(strict_types=1);

namespace App\Year2021\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    /** @var array<string, int> */
    private array $cache = [];

    public function run(): Result
    {
        $fishList = array_map(intval(...), explode(
            ',',
            file_get_contents($this->getFilePath()) ?: throw new \RuntimeException('Unable to read file')
        ));

        $part1 = $part2 = 0;
        foreach ($fishList as $fish) {
            $part1 += $this->countLineage($fish, 80);
            $part2 += $this->countLineage($fish, 256);
        }

        return new Result($part1, $part2);
    }

    /**
     * @param int<0, max> $remainingDays
     */
    private function countLineage(int $timer, int $remainingDays): int
    {
        $cacheKey = "$timer#$remainingDays";

        if (isset($this->cache[$cacheKey])) return $this->cache[$cacheKey];

        if (0 === $remainingDays) return 1;

        --$remainingDays;

        if (0 < $timer) return $this->cache[$cacheKey] = $this->countLineage($timer - 1, $remainingDays);

        return $this->cache[$cacheKey] = $this->countLineage(6, $remainingDays)
            + $this->countLineage(8, $remainingDays);
    }
}
