<?php

declare(strict_types=1);

namespace App\Year2021\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    private const string REGEX = '#^(\d+),(\d+) -> (\d+),(\d+)$#';

    /** @var array<int, array<int, int>> */
    private array $grid1 = [];

    /** @var array<int, array<int, int>> */
    private array $grid2 = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            if (1 !== preg_match(self::REGEX, $line, $matches)) throw new \RuntimeException(\sprintf('Invalid line : %s', $line));

            $x1 = (int) $matches[1];
            $y1 = (int) $matches[2];
            $x2 = (int) $matches[3];
            $y2 = (int) $matches[4];

            if ($x1 === $x2) {
                $start = min($y1, $y2);
                $end = max($y1, $y2);

                for ($y = $start; $y <= $end; ++$y) {
                    $this->grid1[$y][$x1] ??= 0;
                    ++$this->grid1[$y][$x1];

                    $this->grid2[$y][$x1] ??= 0;
                    ++$this->grid2[$y][$x1];
                }

                continue;
            } elseif ($y1 === $y2) {
                $start = min($x1, $x2);
                $end = max($x1, $x2);

                for ($x = $start; $x <= $end; ++$x) {
                    $this->grid1[$y1][$x] ??= 0;
                    ++$this->grid1[$y1][$x];

                    $this->grid2[$y1][$x] ??= 0;
                    ++$this->grid2[$y1][$x];
                }

                continue;
            }

            \assert(abs($x1 - $x2) === abs($y1 - $y2), 'The diagonal isn\'t 45 degrees');

            $stepX = ($x1 < $x2) ? 1 : -1;
            $stepY = ($y1 < $y2) ? 1 : -1;

            $x = $x1;
            foreach (range($y1, $y2, $stepY) as $y) {
                $this->grid2[$y][$x] ??= 0;
                ++$this->grid2[$y][$x];

                $x += $stepX;
            }
        }

        return new Result($this->countIntersections($this->grid1), $this->countIntersections($this->grid2));
    }

    /**
     * @param array<int, int[]> $grid
     */
    private function countIntersections(array $grid): int
    {
        return array_reduce(
            $grid,
            fn (int $lineCarry, array $line): int => $lineCarry + array_reduce(
                $line,
                fn (int $colCarry, int $count): int => $colCarry + ($count >= 2 ? 1 : 0),
                0
            ),
            0
        );
    }
}
