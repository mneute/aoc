<?php

declare(strict_types=1);

namespace App\Year2020\Day09;

use App\AbstractPuzzle;
use App\Result;

final class Day09 extends AbstractPuzzle
{
    public function run(): Result
    {
        $preamble = $this->test ? 5 : 25;
        $file = $this->getFilePath()
            |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Cannot open file'))
            |> (static fn (array $content): array => array_map(intval(...), $content));
        /** @var list<int> $previousNumbers */
        $previousNumbers = [];

        $part1 = $part2 = 0;
        foreach ($file as $index => $line) {
            $currentValue = (int) $line;
            if (\count($previousNumbers) < $preamble) {
                $previousNumbers[] = $currentValue;
                continue;
            }

            if ($this->isValidNumber($previousNumbers, $currentValue)) {
                array_shift($previousNumbers);
                $previousNumbers[] = $currentValue;

                \assert($preamble === \count($previousNumbers));
            } else {
                $part1 = $currentValue;

                $contiguousSet = $file
                    |> (static fn (array $file): array => \array_slice($file, 0, $index))
                    |> (fn (array $set): array => $this->getContiguousSet($set, $currentValue));
                \assert([] !== $contiguousSet);

                $part2 = min($contiguousSet) + max($contiguousSet);

                break;
            }
        }

        return new Result($part1, $part2);
    }

    /**
     * @param list<int> $list
     */
    private function isValidNumber(array $list, int $number): bool
    {
        $size = \count($list);
        foreach ($list as $index => $value) {
            for ($j = $index + 1; $j < $size; ++$j) {
                if ($value + $list[$j] === $number) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param list<int> $list
     *
     * @return list<int>
     */
    private function getContiguousSet(array $list, int $number): array
    {
        $size = \count($list);
        foreach ($list as $index => $value) {
            $sum = $value;
            for ($j = $index + 1; $j < $size; ++$j) {
                $sum += $list[$j];

                if ($number < $sum) {
                    continue 2;
                } elseif ($number === $sum) {
                    return \array_slice($list, $index, $j - $index + 1);
                }
            }
        }

        throw new \InvalidArgumentException('Invalid array');
    }
}
