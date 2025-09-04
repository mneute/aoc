<?php

declare(strict_types=1);

namespace App\Year2023\Day09;

use App\AbstractPuzzle;
use App\Result;

final class Day09 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $numbers = array_map(intval(...), explode(' ', $line));

            $pt1 += $this->getNextValue($numbers);
            $pt2 += $this->getPreviousValue($numbers);
        }

        return new Result($pt1, $pt2);
    }

    /**
     * @param list<int> $numbers
     */
    private function getNextValue(array $numbers): int
    {
        $end = count($numbers) - 1;

        $ecarts = $this->getEcarts($numbers);

        if (array_all($ecarts, fn (int $ecart): bool => 0 === $ecart)) {
            return $numbers[$end];
        }

        return $numbers[$end] + $this->getNextValue($ecarts);
    }

    /**
     * @param list<int> $numbers
     */
    private function getPreviousValue(array $numbers): int
    {
        $ecarts = $this->getEcarts($numbers);

        if (array_all($ecarts, fn (int $ecart): bool => 0 === $ecart)) {
            return $numbers[0];
        }

        return $numbers[0] - $this->getPreviousValue($ecarts);
    }

    /**
     * @param list<int> $numbers
     *
     * @return list<int>
     */
    private function getEcarts(array $numbers): array
    {
        $end = count($numbers) - 1;
        $ecarts = [];
        for ($i = 1; $i <= $end; $i++) {
            $ecarts[] = $numbers[$i] - $numbers[$i - 1];
        }

        return $ecarts;
    }
}
