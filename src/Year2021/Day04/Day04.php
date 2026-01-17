<?php

declare(strict_types=1);

namespace App\Year2021\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    /** @var list<int> */
    private array $drawnNumbers = [];

    /** @var array<int, Board> */
    private array $boards = [];

    public function run(): Result
    {
        $id = 0;
        foreach ($this->readFile() as $i => $line) {
            if (0 === $i) {
                $this->drawnNumbers = array_map(intval(...), explode(',', $line));
                continue;
            }

            if ('' === $line) {
                $this->boards[] = $currentBoard = new Board(++$id);
                continue;
            }

            \assert(isset($currentBoard), '$currentBoard should be set at this point');
            $currentBoard->addElements($line);
        }

        $part1 = $part2 = null;
        foreach ($this->drawnNumbers as $number) {
            foreach ($this->boards as $index => $board) {
                $winning = $board->markElement($number);

                if (!$winning) continue;

                $part1 ??= $board->getSumUnmarkedNumbers() * $number;

                if (1 === \count($this->boards)) {
                    $part2 = $board->getSumUnmarkedNumbers() * $number;
                    break 2;
                }
                unset($this->boards[$index]);
            }
        }

        \assert(\is_int($part1) && \is_int($part2));

        return new Result($part1, $part2);
    }
}
