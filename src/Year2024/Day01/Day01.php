<?php

declare(strict_types=1);

namespace App\Year2024\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        $column1 = [];
        $column2 = [];

        $cachePt2 = [];

        foreach ($this->readFile() as $line) {
            if (1 === preg_match('/^(\d+)\s+(\d+)$/', $line, $matches)) {
                $column1[] = (int) $matches[1];
                $column2[] = (int) $matches[2];
            }
        }

        sort($column1);
        sort($column2);

        foreach (range(0, count($column1) - 1) as $i) {
            $pt1 += abs($column1[$i] - $column2[$i]);

            $pt2 += ($cachePt2[$column1[$i]] ??= $column1[$i] * count(array_filter($column2, static fn (int $j): bool => $column1[$i] === $j)));
        }

        return new Result($pt1, $pt2);
    }
}
