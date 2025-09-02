<?php

declare(strict_types=1);

namespace App\Year2023\Day19;

final readonly class Part
{
    private const string REGEX = '#^\{x=(?<x>\d+),m=(?<m>\d+),a=(?<a>\d+),s=(?<s>\d+)\}$#';

    public int $x;
    public int $m;
    public int $a;
    public int $s;

    public function __construct(
        public string $input,
    ) {
        if (1 !== preg_match(self::REGEX, $this->input, $matches)) {
            throw new \InvalidArgumentException($this->input);
        }

        $this->x = (int) $matches['x'];
        $this->m = (int) $matches['m'];
        $this->a = (int) $matches['a'];
        $this->s = (int) $matches['s'];
    }

    public function getTotal(): int
    {
        return $this->x + $this->m + $this->a + $this->s;
    }
}
