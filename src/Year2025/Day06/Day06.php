<?php

declare(strict_types=1);

namespace App\Year2025\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    public function run(): Result
    {
        return new Result(
            $this->getPart1(),
            $this->getPart2()
        );
    }

    private function getPart1(): int
    {
        $part1 = 0;

        /** @var list<list<int>> $numbers */
        $numbers = [];

        /** @var list<string> $operations */
        $operations = [];
        foreach ($this->readFile() as $line) {
            if (0 !== preg_match_all('#(\d+)#', $line, $matches)) {
                foreach ($matches[1] as $index => $match) {
                    $numbers[$index][] = (int) $match;
                }

                continue;
            }

            preg_match_all('#([*+])#', $line, $matches);
            foreach ($matches[1] as $index => $match) {
                $operations[$index] = $match;
            }
        }

        foreach ($numbers as $index => $list) {
            $part1 += match ($operations[$index]) {
                '+' => array_sum($list),
                '*' => (int) array_product($list),
                default => throw new \RuntimeException(\sprintf('Invalid operation "%s"', $operations[$index])),
            };
        }

        return $part1;
    }

    private function getPart2(): int
    {
        $part2 = 0;

        /** @var list<string> $lines */
        $lines = file($this->getFilePath(), \FILE_IGNORE_NEW_LINES);
        $countLines = \count($lines);
        $lineLength = \strlen($lines[0]);
        \assert(array_all($lines, fn (string $line): bool => \strlen($line) === $lineLength));

        /** @var array<int, int> $numbers */
        $numbers = [];
        for ($j = $lineLength - 1; $j >= 0; --$j) {
            $number = '';
            foreach ($lines as $i => $line) {
                $number .= $line[$j];

                if ($i === $countLines - 1 && \in_array($line[$j], ['+', '*'], true)) {
                    $numbers[] = (int) $number;
                    $part2 += match ($line[$j]) {
                        '+' => array_sum($numbers),
                        '*' => (int) array_product($numbers),
                    };
                }
            }

            if ('' === trim($number, ' ')) {
                $numbers = [];
                continue;
            }

            $numbers[] = (int) $number;
        }

        return $part2;
    }
}
