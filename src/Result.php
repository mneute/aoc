<?php

namespace App;

final readonly class Result
{
    public function __construct(
        public int | string $part1,
        public int | string $part2,
    ) {
    }
}
