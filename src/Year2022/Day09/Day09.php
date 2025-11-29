<?php

declare(strict_types=1);

namespace App\Year2022\Day09;

use App\AbstractPuzzle;
use App\Result;

final class Day09 extends AbstractPuzzle
{
    public function run(): Result
    {
        $tail1Positions = $tail2Positions = [0 => [0 => true]];

        $head1 = new Link(1);
        $head2 = new Link(9);

        foreach ($this->readFile() as $line) {
            [$direction, $count] = explode(' ', $line);

            for ($i = 0; $i < (int) $count; ++$i) {
                [$x, $y] = $head1->move($direction);
                if (!($tail1Positions[$x][$y] ?? false)) $tail1Positions[$x][$y] = true;

                [$x, $y] = $head2->move($direction);
                if (!($tail2Positions[$x][$y] ?? false)) $tail2Positions[$x][$y] = true;
            }
        }

        return new Result(
            array_reduce($tail1Positions, fn (int $carry, array $array): int => $carry + \count($array), 0),
            array_reduce($tail2Positions, fn (int $carry, array $array): int => $carry + \count($array), 0),
        );
    }
}
