<?php

declare(strict_types=1);

namespace App\Year2023\Day05;

final class Mapping
{
    /** @var list<Range> */
    private array $ranges = [];

    public function createRange(int $destination, int $source, int $range): void
    {
        $this->ranges[] = new Range(
            $source,
            $source + $range - 1,
            $destination - $source
        );
    }

    public function transform(int $value): int
    {
        foreach ($this->ranges as $range) {
            if ($range->contains($value)) return $value + $range->transformation;
        }

        return $value;
    }
}
