<?php

declare(strict_types=1);

namespace App\Year2020\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    private const string OCCUPIED = 'x';

    public function run(): Result
    {
        $part1 = $part2 = 0;
        $map = array_fill(0, 128, array_fill(0, 8, '.'));
        foreach ($this->readFile() as $line) {
            \assert(10 === \strlen($line));

            $row = $this->getPosition(str_split(substr($line, 0, 7)), 0, 127);
            $column = $this->getPosition(str_split(substr($line, 7)), 0, 7);

            $map[$row][$column] = self::OCCUPIED;

            $part1 = max($part1, $row * 8 + $column);
        }

        // Test input doesn't have enough values to make sense, we might as well skip this part entirely
        if (!$this->test) {
            foreach ($map as $i => $row) {
                $count = array_reduce($row, static fn (int $carry, string $letter): int => $carry + ('.' === $letter ? 1 : 0), 0);
                if (1 !== $count) continue;

                $previousRow = $map[$i - 1] ?? ['.'];
                $nextRow = $map[$i + 1] ?? ['.'];
                $allOccupied = static fn (string $letter): bool => self::OCCUPIED === $letter;

                if (!array_all($previousRow, $allOccupied) || !array_all($nextRow, $allOccupied)) continue;

                $myRow = $i;
                $myColumn = array_find_key($row, static fn (string $letter): bool => self::OCCUPIED !== $letter);

                $part2 = $myRow * 8 + $myColumn;
                break;
            }
        }

        return new Result($part1, $part2);
    }

    /**
     * @param list<string> $letters
     */
    private function getPosition(array $letters, int $start, int $end): int
    {
        $range = $end - $start + 1;
        \assert(2 ** \count($letters) === $range);
        $middle = $range / 2;
        \assert(\is_int($middle));

        $letter = array_shift($letters);
        if (\in_array($letter, ['F', 'L'], true)) {
            if (2 === $range) return $start;

            return $this->getPosition($letters, $start, $start + $middle - 1);
        } elseif (\in_array($letter, ['B', 'R'], true)) {
            if (2 === $range) return $end;

            return $this->getPosition($letters, $start + $middle, $end);
        }
        throw new \InvalidArgumentException(\sprintf('Unrecognized letter: %s', $letter));
    }
}
