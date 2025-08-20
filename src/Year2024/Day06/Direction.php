<?php

declare(strict_types=1);

namespace App\Year2024\Day06;

enum Direction
{
    case UP;
    case DOWN;
    case LEFT;
    case RIGHT;

    public function next(): self
    {
        return match ($this) {
            self::UP => self::RIGHT,
            self::RIGHT => self::DOWN,
            self::DOWN => self::LEFT,
            self::LEFT => self::UP,
        };
    }
}
