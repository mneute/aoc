<?php

declare(strict_types=1);

namespace App\Year2020\Day01;

use App\AbstractPuzzle;
use App\Result;

final class Day01 extends AbstractPuzzle
{
    private const int TOTAL = 2020;

    /** @var list<int> */
    private array $numbers;
    private int $count;

    public function run(): Result
    {
        $this->numbers = $this->getFilePath()
                |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Could not read file: ' . $path))
                |> (static fn (array $elements): array => array_map(intval(...), $elements));
        $this->count = \count($this->numbers);

        $part1 = $part2 = 0;

        foreach ($this->numbers as $a => $n1) {
            for ($b = $a + 1; $b < $this->count; ++$b) {
                $n2 = $this->numbers[$b];
                if (self::TOTAL === $n1 + $n2) {
                    $part1 = $n1 * $n2;
                    break 2;
                }
            }
        }

        foreach ($this->numbers as $a => $n1) {
            for ($b = $a + 1; $b < $this->count; ++$b) {
                $n2 = $this->numbers[$b];
                for ($c = $b + 1; $c < $this->count; ++$c) {
                    $n3 = $this->numbers[$c];

                    if (self::TOTAL === $n1 + $n2 + $n3) {
                        $part2 = $n1 * $n2 * $n3;
                        break 3;
                    }
                }
            }
        }

        return new Result($part1, $part2);
    }
}
