<?php

declare(strict_types=1);

namespace App\Year2022\Day04;

final readonly class Group
{
    public function __construct(
        public int $start,
        public int $end,
    ) {
        if ($this->start > $this->end) throw new \InvalidArgumentException(sprintf('Invalid group : %d-%d', $this->start, $this->end));
    }

    public function contains(self $other): bool
    {
        return $this->start <= $other->start
            && $this->end >= $other->end;
    }

    public function overlaps(self $other): bool
    {
        return $other->start <= $this->end
            && $other->end >= $this->start;
    }
}
