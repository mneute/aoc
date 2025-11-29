<?php

declare(strict_types=1);

namespace App\Year2023\Day03;

final class Number
{
    public private(set) string $number = '' {
        get => $this->number;
    }
    public bool $valid = false {
        get => $this->valid;
        set => $this->valid = $value;
    }

    public function addChar(string $char): void
    {
        $this->number .= $char;
    }

    public function reset(): void
    {
        $this->number = '';
        $this->valid = false;
    }
}
