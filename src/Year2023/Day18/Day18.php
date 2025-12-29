<?php

declare(strict_types=1);

namespace App\Year2023\Day18;

use App\AbstractPuzzle;
use App\Result;

final class Day18 extends AbstractPuzzle
{
    private const array DIRECTIONS = [
        'R' => [0, 1],
        'L' => [0, -1],
        'D' => [1, 0],
        'U' => [-1, 0],
    ];
    private const string REGEX = '#^(?<direction>[RDLU])\s+(?<count>\d+)\s+\((?<color>\#[a-f0-9]{6})\)$#';

    /** @var array<int, array<int, string>> */
    private array $map = [['#']];

    /** @var array{int, int} */
    private array $currentPosition = [0, 0];
    private int $minI = 0;
    private int $maxI = 0;
    private int $minJ = 0;
    private int $maxJ = 0;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            if (1 !== preg_match(self::REGEX, $line, $matches)) throw new \RuntimeException('Invalid line: ' . $line);
            $direction = $matches['direction'];
            $count = (int) $matches['count'];
            \assert(1 <= $count);

            $pt1 += $count;

            $this->moveCursor($direction, $count);
        }
        ksort($this->map);
        $this->printMap();

        return new Result($pt1, $pt2);
    }

    /**
     * @param int<1, max> $count
     */
    private function moveCursor(string $direction, int $count): void
    {
        for ($i = 1; $i <= $count; ++$i) {
            $newI = $this->currentPosition[0] + self::DIRECTIONS[$direction][0];
            $newJ = $this->currentPosition[1] + self::DIRECTIONS[$direction][1];

            if ($newI > $this->maxI) {
                $this->map[$newI] = array_fill(0, $this->maxJ + 1, '.');
                $this->maxI = $newI;
            } elseif ($newI < $this->minI) {
                $this->map[$newI] = array_fill(0, $this->maxJ + 1, '.');
                $this->minI = $newI;
            }
            if ($newJ > $this->maxJ) {
                foreach ($this->map as &$line) $line[$newJ] = '.';
                $this->maxJ = $newJ;
            } elseif ($newJ < $this->minJ) {
                foreach ($this->map as &$line) $line[$newJ] = '.';
                $this->minJ = $newJ;
            }

            $this->map[$newI][$newJ] = '#';
            $this->currentPosition = [$newI, $newJ];
        }
    }

    private function printMap(): void
    {
        foreach ($this->map as $line) {
            foreach ($line as $char) {
                echo $char;
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
}
