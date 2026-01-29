<?php

declare(strict_types=1);

namespace App\Year2021\Day09;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day09 extends AbstractPuzzle
{
    /** @var array<int, array<int, int>> */
    private array $map = [];

    /** @var list<array{int, int}> */
    private array $lowPoints = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $i => $line) {
            $this->map[$i] = array_map(intval(...), str_split($line));
        }

        return new Result(
            $this->computeRisk(),
            $this->multiplyBassinsSizes()
        );
    }

    private function computeRisk(): int
    {
        $risk = 0;

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $number) {
                foreach (Directions::CARDINALS as $direction) {
                    $i2 = $i + $direction[0];
                    $j2 = $j + $direction[1];

                    if ($number >= ($this->map[$i2][$j2] ?? \PHP_INT_MAX)) continue 2;
                }

                $this->lowPoints[] = [$i, $j];
                $risk += 1 + $number;
            }
        }

        return $risk;
    }

    private function multiplyBassinsSizes(): int
    {
        /** @var \SplMaxHeap<int> $bassinsSizes */
        $bassinsSizes = new \SplMaxHeap();

        /** @var array<int, array<int, bool>> $treated */
        $treated = [];

        foreach ($this->lowPoints as $lowPoint) {
            $bassinSize = 0;

            /** @var Queue<array{int, int}> $queue */
            $queue = new Queue();
            $queue->push($lowPoint);

            foreach ($queue as [$i, $j]) {
                if ($treated[$i][$j] ?? false) continue;

                ++$bassinSize;
                $treated[$i][$j] = true;
                $height = $this->map[$i][$j];

                foreach (Directions::CARDINALS as $direction) {
                    $i2 = $i + $direction[0];
                    $j2 = $j + $direction[1];
                    $h2 = $this->map[$i2][$j2] ?? 9;

                    if ($height < $h2 && $h2 < 9) {
                        $queue->push([$i2, $j2]);
                    }
                }
            }

            $bassinsSizes->insert($bassinSize);
        }

        $count = 0;
        $result = 1;
        foreach ($bassinsSizes as $bassinSize) {
            if (++$count > 3) break;
            $result *= $bassinSize;
        }

        return $result;
    }
}
