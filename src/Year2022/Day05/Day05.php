<?php

declare(strict_types=1);

namespace App\Year2022\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    public function run(): Result
    {
        /** @var array<int, list<string>> $stacks */
        $stacks = [];
        $instructions = [];

        $hasMetEmptyLine = false;

        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasMetEmptyLine = true;
                continue;
            }

            if ($hasMetEmptyLine) {
                $instructions[] = $line;
                continue;
            }

            $parts = str_split($line, 4);

            foreach ($parts as $index => $part) {
                if ('' === trim($part)) {
                    continue;
                }

                if (1 === preg_match('#\[(?<char>\w)]#', $part, $matches)) {
                    $char = $matches['char'];

                    $index++;
                    $stacks[$index] ??= [];
                    $stacks[$index] = [$char, ...$stacks[$index]];
                }
            }
        }
        ksort($stacks);
        $stacksPart1 = $stacks;
        $stacksPart2 = $stacks;

        foreach ($instructions as $instruction) {
            preg_match('#^move (?<count>\d+) from (?<source>\d+) to (?<dest>\d+)$#', $instruction, $matches);
            ['count' => $count, 'source' => $source, 'dest' => $dest] = $matches;

            for ($i = 0; $i < $count; $i++) {
                $box = array_pop($stacksPart1[(int) $source]);
                $stacksPart1[(int) $dest][] = $box;
            }
            $slice = array_splice($stacksPart2[(int) $source], -$count);
            $stacksPart2[(int) $dest] = [...$stacksPart2[(int) $dest], ...$slice];
        }

        $closure = static fn (string $carry, array $stack): string => $carry . end($stack);

        $part1 = array_reduce($stacksPart1, $closure, '');
        $part2 = array_reduce($stacksPart2, $closure, '');

        return new Result($part1, $part2);
    }
}
