<?php

declare(strict_types=1);

namespace App\Year2023\Day15;

final class Lens
{
    public function __construct(
        private(set) readonly string $label,
        public int $focal,
    ) {
    }
}
