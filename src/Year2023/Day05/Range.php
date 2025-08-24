<?php

declare(strict_types=1);

namespace App\Year2023\Day05;

final readonly class Range
{
    public function __construct(
        public int $start,
        public int $end,
        public int $transformation,
    ) {
    }

    public function contains(int $value): bool
    {
        return $this->start <= $value && $value <= $this->end;
    }
}
