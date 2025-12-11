<?php

declare(strict_types=1);

namespace App\Year2025\Day02;

final readonly class Range
{
    public int $start;
    public int $end;
    public int $startLength;
    public int $endLength;

    public function __construct(string $input)
    {
        [$this->start, $this->end] = array_map(intval(...), explode('-', $input));
        \assert($this->start < $this->end);
        $this->startLength = \strlen((string) $this->start);
        $this->endLength = \strlen((string) $this->end);
        \assert(\in_array($this->endLength, [$l = $this->startLength, $l + 1], true));
    }
}
