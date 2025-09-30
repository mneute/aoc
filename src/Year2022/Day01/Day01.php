<?php

declare(strict_types=1);

namespace App\Year2022\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = $current = 0;

        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $pt1 = max($pt1, $current);
                $current = 0;

                continue;
            }

            $current += (int) $line;
        }
        // Just in case the last group is the biggest one
        $pt1 = max($pt1, $current);

        return new Result($pt1, $pt2);
    }
}
