<?php

declare(strict_types=1);

namespace App\Year2020\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    public function run(): Result
    {
        $part1 = $part2 = 0;
        $distinctAnswers = $detailledAnswers = [];
        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $part1 += \count($distinctAnswers);
                $part2 += $this->intersectAnswers($detailledAnswers);
                $distinctAnswers = $detailledAnswers = [];
                continue;
            }

            $answers = count_chars($line, 1);
            $distinctAnswers += $answers;
            $detailledAnswers[] = $answers;
        }
        $part1 += \count($distinctAnswers);
        $part2 += $this->intersectAnswers($detailledAnswers);

        return new Result($part1, $part2);
    }

    /**
     * @param list<array<int, int>> $answers
     */
    private function intersectAnswers(array $answers): int
    {
        return \count(array_intersect_key(...$answers));
    }
}
