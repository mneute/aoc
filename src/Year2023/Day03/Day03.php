<?php

declare(strict_types=1);

namespace App\Year2023\Day03;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    /**
     * @var list<list<string>>
     */
    private array $map = [];

    public function run(): Result
    {
        $this->parseFile();

        $pt1 = $pt2 = 0;

        // Part 1
        foreach ($this->map as $i => $line) {
            $number = new Number();

            foreach ($line as $j => $char) {
                if (is_numeric($char)) {
                    $number->addChar($char);

                    if ($number->valid) continue;

                    $number->valid = $this->hasSymbolAround([$i, $j]);
                } elseif ('' !== $number->number) {
                    if ($number->valid) $pt1 += (int) $number->number;

                    $number->reset();
                }
            }

            if ($number->valid) $pt1 += (int) $number->number;
        }

        // Part 2
        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if ('*' !== $char) continue;

                $numbers = $this->getNumbersAround([$i, $j]);

                if (2 !== \count($numbers)) continue;

                $pt2 += array_reduce(
                    $numbers,
                    fn (int $carry, int $number): int => $carry * $number,
                    1
                );
            }
        }

        return new Result($pt1, $pt2);
    }

    private function parseFile(): void
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = str_split($line);
        }
    }

    /**
     * @param array{0: int, 1: int} $coordinates
     */
    private function hasSymbolAround(array $coordinates): bool
    {
        foreach (Directions::ALL as $direction) {
            $i = $coordinates[0] + $direction[0];
            $j = $coordinates[1] + $direction[1];

            if (!isset($this->map[$i][$j])) continue;     // Out of bounds
            if (is_numeric($this->map[$i][$j])) continue; // Is another number
            if ('.' !== $this->map[$i][$j]) return true;  // Something else than a point ? Victory
        }

        return false;
    }

    /**
     * @param array{0: int, 1: int} $coordinates
     *
     * @return list<int>
     */
    private function getNumbersAround(array $coordinates): array
    {
        /** @var array<int, array<int, true>> $alreadyVisited */
        $alreadyVisited = [];
        $numbers = [];

        foreach (Directions::ALL as $direction) {
            $i = $coordinates[0] + $direction[0];
            $j = $coordinates[1] + $direction[1];

            if (!isset($this->map[$i][$j])) continue;       // Out of bounds
            if (!is_numeric($this->map[$i][$j])) continue;  // NaN
            if ($alreadyVisited[$i][$j] ?? false) continue; // We visited this position when parsing a number

            $alreadyVisited[$i][$j] = true;
            $number = $this->map[$i][$j];

            $j2 = $j + 1;
            while (is_numeric($this->map[$i][$j2] ?? '.')) {
                $number .= $this->map[$i][$j2];
                $alreadyVisited[$i][$j2] = true;
                ++$j2;
            }

            $j2 = $j - 1;
            while (is_numeric($this->map[$i][$j2] ?? '.')) {
                $number = $this->map[$i][$j2] . $number;
                $alreadyVisited[$i][$j2] = true;
                --$j2;
            }

            $numbers[] = (int) $number;
        }

        return $numbers;
    }
}
