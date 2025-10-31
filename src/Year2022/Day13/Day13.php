<?php

declare(strict_types=1);

namespace App\Year2022\Day13;

use App\AbstractPuzzle;
use App\Result;

final class Day13 extends AbstractPuzzle
{
    public function run(): Result
    {
        $left = [];
        $pairId = $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $i => $line) {
            if (0 === ($modulo = $i % 3)) {
                $pairId++;
                $left = json_decode($line, true, flags: \JSON_THROW_ON_ERROR);
                continue;
            } elseif (2 === $modulo) {
                continue;
            }

            $order = $this->isCorrectlyOrdered(
                $left,
                json_decode($line, true, flags: \JSON_THROW_ON_ERROR)
            );
            if (Order::CORRECT === $order) {
                $pt1 += $pairId;
            } elseif (Order::NOT_ENOUGH_INFO === $order) {
                throw new \RuntimeException(sprintf('Pair %d cannot be sorted', $pairId));
            }
        }

        return new Result($pt1, $pt2);
    }

    /**
     * @param list<int|list<int>> $left
     * @param list<int|list<int>> $right
     */
    private function isCorrectlyOrdered(array $left, array $right): Order
    {
        foreach ($left as $i => $leftItem) {
            $rightItem = $right[$i] ?? null;

            if (null === $rightItem) return Order::INCORRECT;

            if (is_int($leftItem) && is_int($rightItem)) {
                if ($leftItem === $rightItem) continue;

                return $leftItem < $rightItem
                    ? Order::CORRECT
                    : Order::INCORRECT;
            }

            // One of the parameters (or both) is an array
            $res = $this->isCorrectlyOrdered((array) $leftItem, (array) $rightItem);
            if ($res === Order::NOT_ENOUGH_INFO) continue;

            return $res;
        }

        return count($left) === count($right)
            ? Order::NOT_ENOUGH_INFO
            : Order::CORRECT;
    }
}
