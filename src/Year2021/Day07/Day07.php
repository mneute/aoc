<?php

declare(strict_types=1);

namespace App\Year2021\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    public function run(): Result
    {
        $positions = $this->getFilePath()
                |> (static fn (string $path): string => file_get_contents($path) ?: throw new \RuntimeException('Unable to read file'))
                |> (static fn (string $content): array => explode(',', $content))
                |> (static fn (array $list): array => array_map(intval(...), $list));

        $start = min($positions);
        $end = max($positions);

        $part1 = $this->getLowestFuelConsumption(
            static fn (int $x): int => array_reduce($positions, static fn (int $carry, int $pos): int => $carry + abs($x - $pos), 0),
            $start,
            $end
        );

        $part2 = $this->getLowestFuelConsumption(
            static fn (int $x): int => array_reduce($positions, static function (int $carry, int $pos) use ($x): int {
                $distance = abs($x - $pos);

                return $carry + (int) ($distance * ($distance + 1) / 2);
            }, 0),
            $start,
            $end
        );

        return new Result($part1, $part2);
    }

    /**
     * @param callable(int): int $getFuelConsumption
     */
    private function getLowestFuelConsumption(callable $getFuelConsumption, int $start, int $end): int
    {
        \assert($start < $end);

        /** @var array<int, int> $cache */
        $cache = [];

        do {
            $m1 = (int) ($start + ($end - $start) / 3);
            $m2 = (int) ($end - ($end - $start) / 3);

            $cache[$m1] ??= $getFuelConsumption($m1);
            $cache[$m2] ??= $getFuelConsumption($m2);

            if ($cache[$m1] < $cache[$m2]) {
                $end = $m2;
            } else {
                $start = $m1;
            }
        } while (2 < $end - $start);

        $list = range($start, $end);

        return $list
            |> (static fn (array $list): array => array_map(static fn (int $x): int => $cache[$x], $list))
            |> min(...);
    }
}
