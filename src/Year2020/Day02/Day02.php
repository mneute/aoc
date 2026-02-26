<?php

declare(strict_types=1);

namespace App\Year2020\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    private const string REGEX = '#^(?<min>\d+)-(?<max>\d+) +(?<letter>[a-z]): (?<pwd>[a-z]+)$#';

    public function run(): Result
    {
        $part1 = $part2 = 0;
        foreach ($this->readFile() as $n => $line) {
            if (1 !== preg_match(self::REGEX, $line, $matches)) throw new \RuntimeException(\sprintf('Invalid line #%d : %s', $n, $line));

            $min = (int) $matches['min'];
            $max = (int) $matches['max'];
            $letter = $matches['letter'];
            $pwd = $matches['pwd'];

            $count = substr_count($pwd, $letter);
            if ($min <= $count && $count <= $max) ++$part1;
            if ($pwd[$min - 1] === $letter xor $pwd[$max - 1] === $letter) ++$part2;
        }

        return new Result($part1, $part2);
    }
}
