<?php

declare(strict_types=1);

namespace App\Year2025\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    /** @var array<int, Range> */
    private array $ranges = [];

    public function run(): Result
    {
        $part1 = 0;
        $hasMetEmptyLine = false;

        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasMetEmptyLine = true;
                continue;
            }

            if (!$hasMetEmptyLine) {
                $this->addRange(new Range($line));
                continue;
            }

            $id = (int) $line;
            if (array_any($this->ranges, fn (Range $range): bool => $range->contains($id))) ++$part1;
        }

        return new Result(
            $part1,
            array_reduce(
                $this->ranges,
                fn (int $carry, Range $range): int => $carry + $range->countElements(),
                0
            )
        );
    }

    private function addRange(Range $range): void
    {
        $overlaps = array_filter(
            $this->ranges,
            fn (Range $r): bool => $r->overlaps($range)
        );

        if ([] === $overlaps) {
            $this->ranges[] = $range;

            return;
        }

        $merged = array_reduce(
            $overlaps,
            function (Range $carry, Range $current): Range {
                \assert($carry->overlaps($current));

                return $carry->merge($current);
            },
            $range
        );
        foreach ($overlaps as $overlap) {
            $key = array_find_key($this->ranges, fn (Range $r): bool => $r === $overlap);
            \assert(\is_int($key));
            unset($this->ranges[$key]);
        }
        $this->ranges[] = $merged;
    }
}
