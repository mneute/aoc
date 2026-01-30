<?php

declare(strict_types=1);

namespace App\Year2021\Day11;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day11 extends AbstractPuzzle
{
    /** @var array<int, array<int, int>> */
    private array $map = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = array_map(intval(...), str_split($line));
        }

        $part1 = 0;
        $part2 = 100;
        for ($i = 0; $i < $part2; ++$i) {
            $part1 += $this->computeStep();
        }

        while (!$this->allZeros()) {
            $this->computeStep();
            ++$part2;
        }

        return new Result($part1, $part2);
    }

    /**
     * Perform the current step and returns the number of flashes that occured.
     */
    private function computeStep(): int
    {
        $flashes = 0;
        /** @var array<int, array<int, true>> $flashed */
        $flashed = [];
        /** @var Queue<array{int, int}> $queue */
        $queue = new Queue();
        foreach ($this->map as $l => &$line) {
            foreach ($line as $c => &$number) {
                if ($number < 9) {
                    ++$number;
                    continue;
                }

                $queue->push([$l, $c]);

                foreach ($queue as [$i, $j]) {
                    if ($flashed[$i][$j] ?? false) continue;

                    if ($this->map[$i][$j] < 9) {
                        ++$this->map[$i][$j];
                        continue;
                    }
                    ++$flashes;
                    $flashed[$i][$j] = true;
                    foreach (Directions::ALL as $direction) {
                        $i2 = $i + $direction[0];
                        $j2 = $j + $direction[1];
                        if (!isset($this->map[$i2][$j2])) continue;
                        $queue->push([$i2, $j2]);
                    }
                }
            }
        }
        unset($line);
        foreach ($flashed as $i => $line) {
            foreach (array_keys($line) as $j) {
                $this->map[$i][$j] = 0;
            }
        }

        return $flashes;
    }

    private function allZeros(): bool
    {
        return array_all(
            $this->map,
            static fn (array $line): bool => array_all($line, static fn (int $n): bool => 0 === $n)
        );
    }
}
