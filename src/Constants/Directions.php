<?php

declare(strict_types=1);

namespace App\Constants;

final class Directions
{
    public const array NORTH = [-1, 0];
    public const array NORTH_EAST = [-1, 1];
    public const array EAST = [0, 1];
    public const array SOUTH_EAST = [1, 1];
    public const array SOUTH = [1, 0];
    public const array SOUTH_WEST = [1, -1];
    public const array WEST = [0, -1];
    public const array NORTH_WEST = [-1, -1];

    public const array CARDINALS = [
        'N' => self::NORTH,
        'E' => self::EAST,
        'S' => self::SOUTH,
        'W' => self::WEST,
    ];

    public const array INTERCARDINALS = [
        'NE' => self::NORTH_EAST,
        'SE' => self::SOUTH_EAST,
        'SW' => self::SOUTH_WEST,
        'NW' => self::NORTH_WEST,
    ];

    public const array ALL = [
        'N' => self::NORTH,
        'NE' => self::NORTH_EAST,
        'E' => self::EAST,
        'SE' => self::SOUTH_EAST,
        'S' => self::SOUTH,
        'SW' => self::SOUTH_WEST,
        'W' => self::WEST,
        'NW' => self::NORTH_WEST,
    ];

    private function __construct()
    {
    }
}
