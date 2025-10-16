<?php

declare(strict_types=1);

namespace App\Year2022\Day09;

final class Link
{
    private const array DIRECTIONS = [
        'U' => [-1, 0],
        'R' => [0, 1],
        'D' => [1, 0],
        'L' => [0, -1],
    ];

    private int $x = 0;
    private int $y = 0;
    private ?self $child = null;

    public function __construct(int $childrens)
    {
        if ($childrens > 0) {
            $this->child = new self($childrens - 1);
        }
    }

    /**
     * Moves the link in the specified direction (U, R, D or L), moves the childrens towards the current chain link and returns the tail's position.
     * @return array{0: int, 1: int}
     */
    public function move(string $direction): array
    {
        $movement = self::DIRECTIONS[$direction] ?? throw new \InvalidArgumentException(sprintf('Unknown direction : %s', $direction));

        $this->x += $movement[0];
        $this->y += $movement[1];

        $this->child?->moveTowards($this);

        return $this->getTailPosition();
    }

    private function moveTowards(self $parent): void
    {
        if ($this->isAdjacent($parent)) return;

        if ($this->x === $parent->x) {
            $this->y += ($this->y > $parent->y) ? -1 : 1;
        } elseif ($this->y === $parent->y) {
            $this->x += ($this->x > $parent->x) ? -1 : 1;
        } else {
            $this->x += ($this->x > $parent->x) ? -1 : 1;
            $this->y += ($this->y > $parent->y) ? -1 : 1;
        }

        $this->child?->moveTowards($this);
    }

    private function isAdjacent(self $other): bool
    {
        $deltaX = $other->x - $this->x;
        $deltaY = $other->y - $this->y;

        return -1 <= $deltaX && $deltaX <= 1
            && -1 <= $deltaY && $deltaY <= 1;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function getTailPosition(): array
    {
        return $this->child?->getTailPosition() ?? [$this->x, $this->y];
    }
}
