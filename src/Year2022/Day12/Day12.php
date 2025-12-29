<?php

declare(strict_types=1);

namespace App\Year2022\Day12;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day12 extends AbstractPuzzle
{
    /** @var list<list<string>> */
    private array $map = [];

    public function run(): Result
    {
        $part1Start = $end = null;
        /** @var list<array{0: int, 1: int}> $part2Starts */
        $part2Starts = [];
        foreach ($this->readFile() as $i => $line) {
            $this->map[] = str_split($line);

            if (false !== ($col = strpos($line, 'S'))) {
                $part1Start = [$i, $col];
            }
            if (false !== ($col = strpos($line, 'E'))) {
                $end = [$i, $col];
            }

            $offset = 0;
            while (false !== ($offset = strpos($line, 'a', $offset))) {
                $part2Starts[] = [$i, $offset];
                ++$offset;
            }
        }
        \assert(\is_array($part1Start), 'No starting point found for part 1');
        \assert(\is_array($end), 'No ending point found');
        \assert(1 <= \count($part2Starts), 'No starting point found for part 2');

        $part1 = $this->dijkstra($part1Start, $end);

        $part2 = \PHP_INT_MAX;
        foreach ($part2Starts as $part2Start) {
            $shortestPath = $this->dijkstra($part2Start, $end);
            if (-1 === $shortestPath) continue;
            $part2 = min($part2, $shortestPath);
        }

        return new Result(
            $part1,
            $part2,
        );
    }

    /**
     * @param array{0: int, 1: int} $start
     * @param array{0: int, 1: int} $end
     */
    private function dijkstra(array $start, array $end): int
    {
        /** @var Queue<array{array{int,int}, int}> $queue */
        $queue = new Queue();
        $queue->push([$start, 0]);

        $visited = [];

        foreach ($queue as [$position, $steps]) {
            if ($position === $end) return $steps;

            $currentElevation = $this->elevation($this->map[$position[0]][$position[1]]);

            foreach (Directions::CARDINALS as $direction) {
                [$x, $y] = [$position[0] + $direction[0], $position[1] + $direction[1]];

                if (!isset($this->map[$x][$y])) continue;

                if ($currentElevation + 1 < $this->elevation($this->map[$x][$y])) continue;

                $key = "$x#$y";
                if ($visited[$key] ?? false) continue;

                $visited[$key] = true;
                $queue->push([[$x, $y], $steps + 1]);
            }
        }

        return -1;
    }

    private function elevation(string $char): int
    {
        static $cache = [];

        return $cache[$char] ??= (\ord(str_replace(['S', 'E'], ['a', 'z'], $char)[0]) - 96);
    }
}
