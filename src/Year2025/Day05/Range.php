<?php

declare(strict_types=1);

namespace App\Year2025\Day05;

final readonly class Range
{
    public private(set) int $start;
    public private(set) int $end;

    public function __construct(string $input)
    {
        [$this->start, $this->end] = array_map(intval(...), explode('-', $input));
        \assert($this->start <= $this->end);
    }

    public function contains(int $value): bool
    {
        return $this->start <= $value
            && $value <= $this->end;
    }

    public function overlaps(self $other): bool
    {
        return $this->start <= $other->end
            && $this->end >= $other->start;
    }

    public function merge(self $other): self
    {
        $start = min($this->start, $other->start);
        $end = max($this->end, $other->end);

        return new self(\sprintf('%d-%d', $start, $end));
    }

    public function countElements(): int
    {
        return $this->end - $this->start + 1;
    }
}
