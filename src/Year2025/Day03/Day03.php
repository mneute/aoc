<?php

declare(strict_types=1);

namespace App\Year2025\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    public function run(): Result
    {
        $part1 = $part2 = 0;

        foreach ($this->readFile() as $line) {
            \assert(is_numeric($line));

            $part1 += $this->findBiggestNumber($line, 2);
            $part2 += $this->findBiggestNumber($line, 12);
        }

        return new Result($part1, $part2);
    }

    /**
     * @param positive-int $digits
     */
    private function findBiggestNumber(string $input, int $digits): int
    {
        \assert(\strlen($input) >= $digits);

        $result = $offset = 0;
        for ($currentChar = 1; $currentChar <= $digits; ++$currentChar) {
            $power = $digits - $currentChar;
            $haystack = substr($input, $offset, -$power ?: null);

            for ($i = 9; $i >= 1; --$i) {
                if (\is_int($position = strpos($haystack, (string) $i))) {
                    $result += $i * 10 ** $power;
                    $offset += $position + 1;

                    continue 2;
                }
            }

            throw new \RuntimeException('This should never happen');
        }

        \assert(10 ** ($digits - 1) <= $result && $result < 10 ** $digits);

        return (int) $result;
    }
}
