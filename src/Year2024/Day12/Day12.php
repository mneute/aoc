<?php

declare(strict_types=1);

namespace App\Year2024\Day12;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;
use Ds\Queue;

final class Day12 extends AbstractPuzzle
{
    /** @var list<list<Block>> */
    private array $map = [];

    public function run(): Result
    {
        $part1 = $part2 = 0;

        foreach ($this->readFile() as $line) {
            $this->map[] = array_map(
                static fn (string $letter): Block => new Block($letter),
                str_split($line),
            );
        }

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $block) {
                if ($block->treated) continue;

                $part1 += $this->getBlockPrice($i, $j, $block->type);
            }
        }

        return new Result($part1, $part2);
    }

    /**
     * Price = area * perimeter.
     */
    private function getBlockPrice(int $i, int $j, string $blockType): int
    {
        $area = $perimeter = 0;

        /** @var Queue<array{int, int}> $queue */
        $queue = new Queue();
        $queue->push([$i, $j]);

        foreach ($queue as $position) {
            [$x, $y] = $position;
            $block = $this->map[$x][$y] ?? throw new \LogicException(\sprintf('Missing block : %d / %d', $i, $j));

            if ($block->treated) continue;

            $block->treated = true;
            ++$area;

            foreach (Directions::CARDINALS as $direction) {
                $i2 = $x + $direction[0];
                $j2 = $y + $direction[1];

                $neighbour = $this->map[$i2][$j2] ?? null;
                if ($blockType === $neighbour?->type) {
                    $queue->push([$i2, $j2]);
                } else {
                    ++$perimeter;
                }
            }
        }

        return $area * $perimeter;
    }
}
