<?php

declare(strict_types=1);

namespace App\Year2024\Day12;

use App\AbstractPuzzle;
use App\Result;

final class Day12 extends AbstractPuzzle
{
    /** @var array<int, array<int, Block>> */
    private array $map = [];

    public function run(): Result
    {
        $part1 = $part2 = 0;

        foreach ($this->readFile() as $i => $line) {
            $this->map[$i] = [];
            foreach (str_split($line) as $j => $char) {
                $this->map[$i][$j] = new Block($char);
            }
        }

        return new Result($part1, $part2);
    }
}
