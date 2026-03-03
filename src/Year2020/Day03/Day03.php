<?php

declare(strict_types=1);

namespace App\Year2020\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    /** @var array<int, array<int, string>> */
    private array $map;
    private int $height;
    private int $width;

    public function run(): Result
    {
        $this->map = $this->getFilePath()
                |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Unable to read file'))
                |> (static fn (array $lines): array => array_map(str_split(...), $lines));
        $this->height = \count($this->map);
        $this->width = \count($this->map[0]);

        $part1 = $this->getTreesEncounter(3, 1);
        $part2 = [[1, 1], [3, 1], [5, 1], [7, 1], [1, 2]]
                |> (fn (array $slopes): array => array_map(fn (array $slope): int => $this->getTreesEncounter(...$slope), $slopes))
                |> (static fn (array $values): int => (int) array_product($values));

        return new Result($part1, $part2);
    }

    private function getTreesEncounter(int $stepRight, int $stepDown): int
    {
        $count = $i = $j = 0;
        while ($i < $this->height) {
            $j = ($j + $stepRight) % $this->width;
            $i += $stepDown;

            if ('#' === ($this->map[$i][$j] ?? null)) ++$count;
        }

        return $count;
    }
}
