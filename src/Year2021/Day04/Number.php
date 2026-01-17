<?php

declare(strict_types=1);

namespace App\Year2021\Day04;

final class Number
{
    public bool $marked = false;

    public function __construct(
        public readonly int $value,
    ) {
    }
}
