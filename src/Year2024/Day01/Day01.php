<?php

declare(strict_types=1);

namespace App\Year2024\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        /** @var \SplMinHeap<int> $heap1 */
        $heap1 = new \SplMinHeap();
        /** @var \SplMinHeap<int> $heap2 */
        $heap2 = new \SplMinHeap();

        $column2Count = [];

        foreach ($this->readFile() as $line) {
            if (1 !== preg_match('/^(\d+) +(\d+)$/', $line, $matches)) {
                throw new \RuntimeException(\sprintf('Invalid line : %s', $line));
            }

            [1 => $a, 2 => $b] = $matches;

            $heap1->insert((int) $a);
            $heap2->insert((int) $b);
            $column2Count[$b] ??= 0;
            ++$column2Count[$b];
        }

        $heap2->rewind();
        foreach ($heap1 as $item1) {
            $item2 = $heap2->current();
            $heap2->next();

            $pt1 += abs($item1 - $item2);
            $pt2 += $item1 * ($column2Count[$item1] ?? 0);
        }

        return new Result($pt1, $pt2);
    }
}
