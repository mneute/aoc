<?php

declare(strict_types=1);

namespace App\Year2020\Day10;

use App\AbstractPuzzle;
use App\Result;

final class Day10 extends AbstractPuzzle
{
    /** @var non-empty-list<int> */
    private array $adapters;

    /** @var non-empty-array<int, int> */
    private array $paths;

    public function run(): Result
    {
        $this->adapters = $this->getFilePath()
            |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Cannot read file'))
            |> (static fn (array $list): array => array_map(intval(...), $list));
        sort($this->adapters);
        $count = \count($this->adapters);

        \assert($count === \count(array_unique($this->adapters)));
        $this->paths = array_fill(0, $count, 0);

        $joltage = $diff1 = $diff3 = 0;

        foreach ($this->adapters as $adapter) {
            match ($adapter - $joltage) {
                1 => ++$diff1,
                3 => ++$diff3,
                default => null,
            };
            $joltage = $adapter;
        }
        ++$diff3;

        for ($index = $count - 1; $index >= 0; --$index) {
            if ($index === $count - 1) {
                $this->paths[$index] = 1;
                continue;
            }

            $this->paths[$index] = $this->countPathsAtIndex($index);
        }

        return new Result($diff1 * $diff3, $this->countPathsAtIndex(-1));
    }

    private function countPathsAtIndex(int $index): int
    {
        $joltage = $this->adapters[$index] ?? 0;

        return $this->paths
            |> (static fn (array $paths): array => \array_slice($paths, $index + 1, 3, true))
            |> (array_keys(...))
            |> (fn (array $keys): array => array_filter($keys, fn (int $key): bool => $this->adapters[$key] <= $joltage + 3))
            |> (fn (array $keys): array => array_map(fn (int $key): int => $this->paths[$key], $keys))
            |> array_sum(...);
    }
}
