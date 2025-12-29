<?php

declare(strict_types=1);

namespace App\Year2024\Day18;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day18 extends AbstractPuzzle
{
    private const int MAX_VALUE = 70;
    private const int MAX_FALLEN_BYTES = 1024;

    /** @var array<int, array<int, string>> */
    private array $map = [];

    public function run(): Result
    {
        $this->parseFile();

        return new Result(
            $this->dijkstra(),
            0,
        );
    }

    private function parseFile(): void
    {
        $this->map = array_fill(
            0,
            self::MAX_VALUE + 1,
            array_fill(0, self::MAX_VALUE + 1, '.')
        );

        $lineCount = 0;
        foreach ($this->readFile() as $line) {
            if ($lineCount++ >= self::MAX_FALLEN_BYTES) {
                break;
            }

            [$x, $y] = array_map(intval(...), explode(',', $line));

            $this->map[$y][$x] = '#';
        }
    }

    private function dijkstra(): int
    {
        $start = [0, 0];
        $end = [self::MAX_VALUE, self::MAX_VALUE];

        /** @var Queue<array{array{int, int}, int}> $queue */
        $queue = new Queue();
        $queue->push([$start, 0]);

        $visited = [];

        foreach ($queue as [$position, $steps]) {
            if ($position === $end) return $steps;

            foreach (Directions::CARDINALS as $direction) {
                [$x, $y] = [$position[0] + $direction[0], $position[1] + $direction[1]];

                if ($x < 0 || $y < 0 || $x > self::MAX_VALUE || $y > self::MAX_VALUE) continue;

                if ('#' === $this->map[$y][$x]) continue;

                $key = "$x-$y";
                if ($visited[$key] ?? false) continue;

                $visited[$key] = true;
                $queue->push([[$x, $y], $steps + 1]);
            }
        }

        return -1;
    }
}
