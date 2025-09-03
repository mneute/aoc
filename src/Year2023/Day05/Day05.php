<?php

declare(strict_types=1);

namespace App\Year2023\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    /** @var list<int> */
    private array $seeds;

    /** @var list<Mapping> */
    private array $mappings = [];

    public function run(): Result
    {
        $this->parseFile();

        $pt1 = PHP_INT_MAX;
        foreach ($this->seeds as $seed) {
            $pt1 = min($pt1, $this->getLocation($seed));
        }

        return new Result($pt1, 0);
    }

    private function parseFile(): void
    {
        $currentIndex = -1;

        foreach ($this->readFile() as $i => $line) {
            if (0 === $i) {
                $this->seeds = $this->getNumbers($line);
                continue;
            }

            if ('' === $line) continue;
            if (str_ends_with($line, 'map:')) {
                $this->mappings[++$currentIndex] = new Mapping();
                continue;
            }

            $this->mappings[$currentIndex]->createRange(...$this->getNumbers($line));
        }
    }

    /**
     * @return list<int>
     */
    private function getNumbers(string $line): array
    {
        preg_match_all('#\b\d+\b#', $line, $matches);

        return array_map(intval(...), $matches[0]);
    }

    private function getLocation(int $seed): int
    {
        return array_reduce(
            $this->mappings,
            static fn (int $value, Mapping $mapping): int => $mapping->transform($value),
            $seed,
        );
    }
}
