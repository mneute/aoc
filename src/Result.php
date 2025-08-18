<?php

namespace App;

final readonly class Result
{
    public function __construct(
        public int $part1,
        public int $part2,
    ) {
    }
}
