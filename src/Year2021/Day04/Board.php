<?php

declare(strict_types=1);

namespace App\Year2021\Day04;

use App\Constants\Directions;

final class Board
{
    private const int GRID_SIZE = 5;

    /** @var list<list<Number>> */
    private array $elements = [];

    public function __construct(
        public readonly int $id,
    ) {
    }

    public function addElements(string $line): void
    {
        $this->elements[] = array_map(
            fn (string $elt): Number => new Number((int) $elt),
            str_split($line, 3)
        );
    }

    /**
     * @return bool true if the grid is winning, false otherwise
     */
    public function markElement(int $element): bool
    {
        $position = null;
        foreach ($this->elements as $i => $line) {
            foreach ($line as $j => $number) {
                if ($number->value === $element) {
                    $number->marked = true;
                    $position = [$i, $j];
                    break 2;
                }
            }
        }

        if (\is_array($position)) return $this->positionWinsTheGrid($position);

        return false;
    }

    public function getSumUnmarkedNumbers(): int
    {
        return array_reduce(
            $this->elements,
            fn (int $lineCarry, array $line): int => $lineCarry + array_reduce(
                $line,
                fn (int $colCarry, Number $n): int => $colCarry + ($n->marked ? 0 : $n->value),
                0
            ),
            0
        );
    }

    /**
     * @param array{int, int} $position
     */
    private function positionWinsTheGrid(array $position): bool
    {
        $couples = [
            [Directions::NORTH, Directions::SOUTH],
            [Directions::EAST, Directions::WEST],
        ];

        foreach ($couples as $couple) {
            $count = 1;
            foreach ($couple as $direction) {
                $currPos = [$position[0] + $direction[0], $position[1] + $direction[1]];

                while (($element = $this->elements[$currPos[0]][$currPos[1]] ?? null) instanceof Number) {
                    if (!$element->marked) continue 3;

                    $currPos[0] += $direction[0];
                    $currPos[1] += $direction[1];
                    ++$count;
                }
            }

            if (self::GRID_SIZE === $count) return true;
        }

        return false;
    }
}
