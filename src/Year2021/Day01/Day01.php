<?php

declare(strict_types=1);

namespace App\Year2021\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    public function run(): Result
    {
        $part1 = $part2 = 0;

        $prev1 = $prev2 = $prevSum = null;
        foreach ($this->readFile() as $line) {
            $currentValue = (int) $line;
            if ($currentValue > ($prev1 ?? \PHP_INT_MAX)) ++$part1;

            if (null !== $prev2) {
                $sum = $currentValue + $prev1 + $prev2;

                if ($sum > ($prevSum ?? \PHP_INT_MAX)) ++$part2;

                $prevSum = $sum;
            }

            [$prev1, $prev2] = [$currentValue, $prev1];
        }

        return new Result($part1, $part2);
    }
}
