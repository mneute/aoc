<?php

declare(strict_types=1);

namespace App\Year2020\Day11;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;

final class Day11 extends AbstractPuzzle
{
    private const string FREE = 'L';
    private const string OCCUPIED = '#';
    private const string FLOOR = '.';

    /** @var array<int, array<int, string>> */
    private array $map;
    private bool $isStabilized = false;

    public function run(): Result
    {
        $this->map = $this->getFilePath()
            |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Cannot read file'))
            |> (static fn (array $content): array => array_map(str_split(...), $content));

        do {
            $this->moveSeats();
        } while (!$this->isStabilized);

        return new Result($this->countOccupiedSeats(), 0);
    }

    private function moveSeats(): void
    {
        $currentRound = [];

        $this->isStabilized = true;

        foreach ($this->map as $row => $line) {
            foreach ($line as $col => $seat) {
                if (self::FREE === $seat && !$this->tooManyNeighbors($row, $col, 1)) {
                    $currentRound[$row][$col] = self::OCCUPIED;
                    $this->isStabilized = false;
                } elseif (self::OCCUPIED === $seat && $this->tooManyNeighbors($row, $col, 4)) {
                    $currentRound[$row][$col] = self::FREE;
                    $this->isStabilized = false;
                } else {
                    $currentRound[$row][$col] = $seat;
                }
            }
        }

        $this->map = $currentRound;
    }

    private function tooManyNeighbors(int $row, int $col, int $threshold): bool
    {
        $neighbors = Directions::ALL
            |> (fn (array $directions): array => array_map(fn (array $direction): string => $this->map[$row + $direction[0]][$col + $direction[1]] ?? self::FLOOR, $directions))
            |> (static fn (array $neighbors): array => array_filter($neighbors, static fn (string $neighbor): bool => self::OCCUPIED === $neighbor))
            |> count(...);

        return $neighbors >= $threshold;
    }

    private function countOccupiedSeats(): int
    {
        return array_reduce(
            $this->map,
            static fn (int $carry, array $line): int => $carry + \count(array_filter($line, static fn (string $seat): bool => self::OCCUPIED === $seat)),
            0
        );
    }
}
