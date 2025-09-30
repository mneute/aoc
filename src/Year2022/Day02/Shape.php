<?php

declare(strict_types=1);

namespace App\Year2022\Day02;

enum Shape: int
{
    case ROCK = 1;
    case PAPER = 2;
    case SCISSORS = 3;

    public static function create(string $input): self
    {
        return match ($input) {
            'A', 'X' => self::ROCK,
            'B', 'Y' => self::PAPER,
            'C', 'Z' => self::SCISSORS,
            default => throw new \InvalidArgumentException(sprintf('Unknown value %s', $input)),
        };
    }

    public static function guess(self $theirs, string $outcome): self
    {
        return match ($outcome) {
            'X' => match ($theirs) {
                self::ROCK => self::SCISSORS,
                self::PAPER => self::ROCK,
                self::SCISSORS => self::PAPER,
            },
            'Y' => $theirs,
            'Z' => match ($theirs) {
                self::ROCK => self::PAPER,
                self::PAPER => self::SCISSORS,
                self::SCISSORS => self::ROCK,
            },
            default => throw new \InvalidArgumentException(sprintf('Unknown value %s', $outcome)),
        };
    }

    public function compare(self $other): int
    {
        $lose = 0;
        $draw = 3;
        $win = 6;

        return match ($this) {
            self::ROCK => match ($other) {
                self::ROCK => $draw,
                self::PAPER => $lose,
                self::SCISSORS => $win,
            },
            self::PAPER => match ($other) {
                self::ROCK => $win,
                self::PAPER => $draw,
                self::SCISSORS => $lose,
            },
            self::SCISSORS => match ($other) {
                self::ROCK => $lose,
                self::PAPER => $win,
                self::SCISSORS => $draw,
            },
        };
    }
}
