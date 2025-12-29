<?php

declare(strict_types=1);

namespace App\Year2024\Day10;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day10 extends AbstractPuzzle
{
    /** @var list<list<string>> */
    private array $map = [];

    public function run(): Result
    {
        $part1 = $part2 = 0;

        foreach ($this->readFile() as $line) {
            $this->map[] = str_split($line);
        }

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if ('0' === $char) {
                    $part1 += $this->getTrailheadScore($i, $j);
                    $part2 += $this->getTrailheadScore($i, $j, false);
                }
            }
        }

        return new Result($part1, $part2);
    }

    private function getTrailheadScore(int $i, int $j, bool $part1 = true): int
    {
        $score = 0;
        /** @var array<int, array<int, bool>> $cache */
        $cache = [];

        /** @var Queue<array{int, int}> $queue */
        $queue = new Queue();
        $queue->push([$i, $j]);

        foreach ($queue as $position) {
            [$x, $y] = $position;

            $height = (int) ($this->map[$x][$y] ?? throw new \LogicException('Invalid position'));

            if (9 === $height) {
                if ($part1 && isset($cache[$x][$y])) continue;

                ++$score;
                $cache[$x][$y] = true;
                continue;
            }
            foreach (Directions::CARDINALS as $direction) {
                $i2 = $x + $direction[0];
                $j2 = $y + $direction[1];

                if ($height + 1 === (int) ($this->map[$i2][$j2] ?? -1)) {
                    $queue->push([$i2, $j2]);
                }
            }
        }

        return $score;
    }
}
