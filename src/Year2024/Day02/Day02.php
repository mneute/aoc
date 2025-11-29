<?php

declare(strict_types=1);

namespace App\Year2024\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $data = explode(' ', $line);

            if ($this->isSafe($data)) {
                ++$pt1;
                ++$pt2;

                continue;
            }

            foreach (range(0, \count($data) - 1) as $i) {
                $copy = $data;
                array_splice($copy, $i, 1);

                if ($this->isSafe($copy)) {
                    ++$pt2;

                    continue 2;
                }
            }
        }

        return new Result($pt1, $pt2);
    }

    private function isSafe(array $data): bool
    {
        $max = \count($data) - 2;
        $steps = [];
        for ($i = 0; $i <= $max; ++$i) {
            $steps[] = $data[$i + 1] - $data[$i];
        }

        return array_all($steps, static fn (int $step): bool => 1 <= $step && $step <= 3)
            || array_all($steps, static fn (int $step): bool => -3 <= $step && $step <= -1);
    }
}
