<?php

declare(strict_types=1);

namespace App\Year2025\Day08;

final readonly class Distance
{
    public float $distance;

    public function __construct(
        public Point $a,
        public Point $b,
    ) {
        $this->distance = $a->distanceTo($b);
    }

    public function compareTo(self $other): int
    {
        return $this->distance <=> $other->distance;
    }
}
