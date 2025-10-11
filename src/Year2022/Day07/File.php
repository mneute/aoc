<?php

declare(strict_types=1);

namespace App\Year2022\Day07;

final readonly class File
{
    public function __construct(
        public string $name,
        public int $size,
    ) {
    }
}
