<?php

declare(strict_types=1);

namespace App\Year2023\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        $hands = [];
        foreach ($this->readFile() as $line) {
            preg_match('#^(?<hand>[AKQJT2-9]{5})\s+(?<bid>\d+)$#', $line, $matches);
            \assert(\array_key_exists('hand', $matches) && \array_key_exists('bid', $matches));

            $hands[] = new Hand($matches['hand'], (int) $matches['bid']);
        }

        usort($hands, $this->part1Sort(...));
        foreach ($hands as $index => $hand) {
            $pt1 += ($index + 1) * $hand->bid;
        }

        usort($hands, $this->part2Sort(...));
        foreach ($hands as $index => $hand) {
            $pt2 += ($index + 1) * $hand->bid;
        }

        return new Result($pt1, $pt2);
    }

    private function part1Sort(Hand $a, Hand $b): int
    {
        if ($a->part1Type !== $b->part1Type) {
            return $a->part1Type->value <=> $b->part1Type->value;
        }

        return $a->part1Weight <=> $b->part1Weight;
    }

    private function part2Sort(Hand $a, Hand $b): int
    {
        if ($a->part2Type !== $b->part2Type) {
            return $a->part2Type->value <=> $b->part2Type->value;
        }

        return $a->part2Weight <=> $b->part2Weight;
    }
}
