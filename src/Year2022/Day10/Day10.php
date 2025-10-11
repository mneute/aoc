<?php

declare(strict_types=1);

namespace App\Year2022\Day10;

use App\AbstractPuzzle;
use App\Result;

final class Day10 extends AbstractPuzzle
{
    private int $x = 1;
    private int $cycle = 0;

    public function run(): Result
    {
        $pt1 = 0;
        foreach ($this->readFile() as $line) {
            $parts = explode(' ', $line);
            $operation = $parts[0];

            $cycles = ('noop' === $operation ? 1 : 2);
            for ($i = 0; $i < $cycles; $i++) {
                $this->cycle++;

                if (0 === ($this->cycle + 20) % 40) {
                    $pt1 += ($this->cycle * $this->x);
                }
            }

            if ('addx' === $operation) {
                $this->x += ((int) $parts[1] ?? throw new \RuntimeException(sprintf('Missing value for addx operation : %s', $line)));
            }
        }

        return new Result($pt1, 0);
    }
}
