<?php

declare(strict_types=1);

namespace App\Year2020\Day04;

final readonly class Height
{
    private int $height;
    private string $unit;

    public function __construct(string $input)
    {
        if (1 !== preg_match('/^(\d+)(\w+)$/', $input, $matches)) throw new \InvalidArgumentException(\sprintf('Invalid height : %s', $input));

        $this->height = (int) $matches[1];
        $this->unit = $matches[2];
    }

    public function isValid(): bool
    {
        return match ($this->unit) {
            'cm' => 150 <= $this->height && $this->height <= 193,
            'in' => 59 <= $this->height && $this->height <= 76,
            default => false,
        };
    }
}
