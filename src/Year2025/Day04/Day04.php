<?php

declare(strict_types=1);

namespace App\Year2025\Day04;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day04 extends AbstractPuzzle
{
    private const string ROLL_OF_PAPER = '@';
    private const int MAX_COUNT = 4;

    /** @var array<int, array<int, string>> */
    private array $map = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = str_split($line);
        }

        $part1 = $part2 = 0;

        /** @var Queue<array{int, int}> $queue */
        $queue = new Queue();

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if (self::ROLL_OF_PAPER !== $char || !$this->isAccessible($i, $j)) continue;

                ++$part1;
                $queue->push([$i, $j]);
            }
        }
        foreach ($queue as [$i, $j]) {
            if (self::ROLL_OF_PAPER !== $this->map[$i][$j] || !$this->isAccessible($i, $j)) continue;

            $this->map[$i][$j] = '.';
            ++$part2;

            foreach (Directions::ALL as [$stepI, $stepJ]) {
                $i2 = $i + $stepI;
                $j2 = $j + $stepJ;
                if (self::ROLL_OF_PAPER === ($this->map[$i2][$j2] ?? null)) $queue->push([$i2, $j2]);
            }
        }

        return new Result($part1, $part2);
    }

    private function isAccessible(int $i, int $j): bool
    {
        $count = 0;

        foreach (Directions::ALL as [$stepI, $stepJ]) {
            $i2 = $i + $stepI;
            $j2 = $j + $stepJ;
            if (!isset($this->map[$i2][$j2])) continue;
            if (self::ROLL_OF_PAPER === $this->map[$i2][$j2]) ++$count;
            if (self::MAX_COUNT === $count) return false;
        }

        return true;
    }
}
