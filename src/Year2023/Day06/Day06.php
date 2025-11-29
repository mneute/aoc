<?php

declare(strict_types=1);

namespace App\Year2023\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    /** @var array<int> */
    private array $times = [];

    /** @var array<int> */
    private array $distances = [];

    public function run(): Result
    {
        $this->parseFile();

        $pt1 = 1;
        foreach ($this->times as $race => $time) {
            $pt1 *= $this->howManyWaysToBeatRecord($time, $this->distances[$race]);
        }

        $reducer = static fn (string $carry, int $value): string => $carry . $value;

        $time = (int) array_reduce($this->times, $reducer, '');
        $distanceToBeat = (int) array_reduce($this->distances, $reducer, '');

        $pt2 = $this->howManyWaysToBeatRecord($time, $distanceToBeat);

        return new Result($pt1, $pt2);
    }

    private function parseFile(): void
    {
        foreach ($this->readFile() as $i => $line) {
            preg_match_all('#\b\d+\b#', $line, $matches);

            if (0 === $i) {
                $this->times = array_map(intval(...), $matches[0]);
            } else {
                $this->distances = array_map(intval(...), $matches[0]);
            }
        }

        if (\count($this->times) !== \count($this->distances)) {
            throw new \LogicException("Invalid input, the columns don't match");
        }
    }

    private function howManyWaysToBeatRecord(int $time, int $distanceToBeat): int
    {
        $firstToBeat = 1;
        $lastToBeat = $time - 1;

        while ($this->getDistance($time, $firstToBeat) <= $distanceToBeat) ++$firstToBeat;
        while ($this->getDistance($time, $lastToBeat) <= $distanceToBeat) --$lastToBeat;

        return $lastToBeat - $firstToBeat + 1;
    }

    private function getDistance(int $time, int $speed): int
    {
        return $speed * ($time - $speed);
    }
}
