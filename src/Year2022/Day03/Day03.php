<?php

declare(strict_types=1);

namespace App\Year2022\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    private const int UPPERCASE_A = 65;
    private const int UPPERCASE_Z = 90;
    private const int LOWERCASE_A = 97;
    private const int LOWERCASE_Z = 122;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $i => $line) {
            $length = strlen($line);
            if (0 !== $length % 2) throw new \RuntimeException(sprintf('Line %d has an odd number of characters', $i));

            [$left, $right] = str_split($line, $length / 2);

            $uniqueLeft = array_unique(str_split($left));
            $uniqueRight = array_unique(str_split($right));

            $intersect = array_values(array_intersect($uniqueLeft, $uniqueRight));
            if (1 !== count($intersect)) throw new \RuntimeException(sprintf('Line %d has more than one characters in common in both parts', $i));

            $priority = ord($intersect[0]);
            if (self::UPPERCASE_A <= $priority && $priority <= self::UPPERCASE_Z) {
                $priority -= (self::UPPERCASE_A - 27);
            } elseif (self::LOWERCASE_A <= $priority && $priority <= self::LOWERCASE_Z) {
                $priority -= (self::LOWERCASE_A - 1);
            } else {
                throw new \RuntimeException(sprintf('Unknown character %s', $intersect[0]));
            }

            $pt1 += $priority;
        }

        return new Result($pt1, $pt2);
    }
}
