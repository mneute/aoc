<?php

declare(strict_types=1);

namespace App\Year2022\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    private int $pt1 = 0;

    /** @var list<int> */
    private array $pt2 = [];

    public function run(): Result
    {
        $current = 0;
        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $this->updateCounters($current);
                $current = 0;

                continue;
            }

            $current += (int) $line;
        }
        // One last update when we reached the end of the file
        $this->updateCounters($current);

        return new Result($this->pt1, array_sum($this->pt2));
    }

    private function updateCounters(int $current): void
    {
        $this->pt1 = max($this->pt1, $current);

        if (count($this->pt2) < 3) {
            $this->pt2[] = $current;
            rsort($this->pt2);
            return;
        }

        foreach ($this->pt2 as $index => $item) {
            if ($item < $current) {
                array_splice($this->pt2, $index, 0, $current);
                $this->pt2 = array_slice($this->pt2, 0, 3);
                return;
            }
        }
    }
}
