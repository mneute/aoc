<?php

declare(strict_types=1);

namespace App\Year2024\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $data = array_map(intval(...), explode(' ', $line));

            $expectedResult = array_shift($data);

            if ($this->isPossible($expectedResult, $data)) {
                $pt1 += $expectedResult;
                $pt2 += $expectedResult;
            } elseif ($this->isPossible($expectedResult, $data, true)) {
                $pt2 += $expectedResult;
            }
        }

        return new Result($pt1, $pt2);
    }

    /**
     * @param int[] $data
     */
    private function isPossible(int $expectedResult, array $data, bool $pt2 = false): bool
    {
        if (count($data) === 1) return $expectedResult === $data[0];

        $n1 = array_shift($data);
        $n2 = array_shift($data);

        if ($n1 > $expectedResult) return false;

        return $this->isPossible($expectedResult, [$n1 + $n2, ...$data], $pt2)
            || $this->isPossible($expectedResult, [$n1 * $n2, ...$data], $pt2)
            || ($pt2 && $this->isPossible($expectedResult, [(int) ($n1.$n2), ...$data], $pt2));
    }
}
