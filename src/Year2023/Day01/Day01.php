<?php

declare(strict_types=1);

namespace App\Year2023\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1Chars = array_map(strval(...), range(1, 9));
        $pt2Chars = [...$pt1Chars, 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $firstNumberPt1 = $this->getFirstOccurence($line, $pt1Chars);
            $lastNumberPt1 = $this->getLastOccurence($line, $pt1Chars);
            $numberPt1 = (int) ($firstNumberPt1 . $lastNumberPt1);

            $pt1 += $numberPt1;

            $firstNumberPt2 = $this->getFirstOccurence($line, $pt2Chars);
            $lastNumberPt2 = $this->getLastOccurence($line, $pt2Chars);
            $numberPt2 = (int) ($firstNumberPt2 . $lastNumberPt2);

            $pt2 += $numberPt2;
        }

        return new Result($pt1, $pt2);
    }

    private function getFirstOccurence(string $input, array $needles): int
    {
        $min = \PHP_INT_MAX;
        $value = -1;

        foreach ($needles as $index => $needle) {
            $pos = strpos($input, (string) $needle);
            if (false === $pos) continue;

            if ($pos < $min) {
                $min = $pos;
                $value = ($index % 9) + 1; // Because of the structure of the array, "1" becomes 1, "one" becomes 1, "2" becomes 2 etc.
            }
        }

        return $value;
    }

    private function getLastOccurence(string $input, array $needles): int
    {
        $max = $value = -1;

        foreach ($needles as $index => $needle) {
            $pos = strrpos($input, (string) $needle);
            if (false === $pos) continue;

            if ($pos > $max) {
                $max = $pos;
                $value = ($index % 9) + 1; // Because of the structure of the array, "1" becomes 1, "one" becomes 1, "2" becomes 2 etc.
            }
        }

        return $value;
    }
}
