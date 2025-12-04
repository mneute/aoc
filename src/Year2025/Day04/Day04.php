<?php

declare(strict_types=1);

namespace App\Year2025\Day04;

use App\AbstractPuzzle;
use App\Result;
use Ds\Queue;

final class Day04 extends AbstractPuzzle
{
    private const string ROLL_OF_PAPER = '@';
    private const int MAX_COUNT = 4;
    private const array DIRECTIONS = [
        'N' => [-1, 0],
        'NE' => [-1, 1],
        'E' => [0, 1],
        'SE' => [1, 1],
        'S' => [1, 0],
        'SW' => [1, -1],
        'W' => [0, -1],
        'NW' => [-1, -1],
    ];

    /** @var array<int, array<int, string>> */
    private array $map = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = str_split($line);
        }

        $queue = $this->getAccessibleRolls();
        $part1 = $part2 = $queue->count();

        do {
            $this->removeRolls($queue);

            $queue = $this->getAccessibleRolls();
            $part2 += $queue->count();
        } while (0 !== $queue->count());

        return new Result(
            $part1,
            $part2
        );
    }

    /**
     * @return Queue<array{int, int}>
     */
    private function getAccessibleRolls(): Queue
    {
        $queue = new Queue();
        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if (self::ROLL_OF_PAPER === $char && $this->isAccessible($i, $j)) {
                    $queue->push([$i, $j]);
                }
            }
        }

        return $queue;
    }

    private function isAccessible(int $i, int $j): bool
    {
        $count = 0;

        foreach (self::DIRECTIONS as [$stepI, $stepJ]) {
            $i2 = $i + $stepI;
            $j2 = $j + $stepJ;
            if (!isset($this->map[$i2][$j2])) continue;
            if (self::ROLL_OF_PAPER === $this->map[$i2][$j2]) ++$count;
            if (self::MAX_COUNT === $count) return false;
        }

        return true;
    }

    /**
     * @param Queue<array{int, int}> $queue
     */
    private function removeRolls(Queue $queue): void
    {
        foreach ($queue as [$i, $j]) {
            $this->map[$i][$j] = '.';
        }
    }
}
