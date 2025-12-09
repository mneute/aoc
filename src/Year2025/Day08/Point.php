<?php

declare(strict_types=1);

namespace App\Year2025\Day08;

final readonly class Point implements \Stringable
{
    public int $x;
    public int $y;
    public int $z;

    public function __construct(
        public string $id,
    ) {
        [$this->x, $this->y, $this->z] = array_map(intval(...), explode(',', $this->id));
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function distanceTo(self $other): float
    {
        return sqrt(
            ($this->x - $other->x) ** 2
            + ($this->y - $other->y) ** 2
            + ($this->z - $other->z) ** 2
        );
    }
}
