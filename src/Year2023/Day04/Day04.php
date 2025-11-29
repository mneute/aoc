<?php

declare(strict_types=1);

namespace App\Year2023\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    public function run(): Result
    {
        $cards = [];

        $pt1 = 0;

        foreach ($this->readFile() as $line) {
            preg_match('#^Card\s+(?<id>\d+):(?<winning>(?: +\d+)+) \|(?<drawn>(?: +\d+)+)$#', $line, $matches);

            $winning = preg_replace('#\s{2,}#', ' ', trim($matches['winning']));
            $drawn = preg_replace('#\s{2,}#', ' ', trim($matches['drawn']));

            $winningNumbers = array_map(intval(...), explode(' ', (string) $winning));
            $drawnNumbers = array_map(intval(...), explode(' ', (string) $drawn));

            $intersection = \count(array_intersect($winningNumbers, $drawnNumbers));

            if (0 !== $intersection) {
                $pt1 += (2 ** ($intersection - 1));
            }

            $id = (int) $matches['id'];
            $cards[$id] ??= new Card($id);
            $cards[$id]->originalCount = 1;

            if (0 === $intersection) continue;

            foreach (range(1, $intersection) as $item) {
                $nextCard = $id + $item;
                $cards[$nextCard] ??= new Card($nextCard);
                $cards[$nextCard]->copyCount += $cards[$id]->getCount();
            }
        }

        $pt2 = array_reduce(
            $cards,
            fn (int $carry, Card $card): int => $carry + $card->getCount(),
            0
        );

        return new Result($pt1, $pt2);
    }
}
