<?php

declare(strict_types=1);

namespace App\Year2024\Day12;

final class Block
{
    public bool $treated = false;

    public function __construct(
        public readonly string $type,
    ) {
    }
}
