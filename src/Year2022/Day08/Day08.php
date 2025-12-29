<?php

declare(strict_types=1);

namespace App\Year2022\Day08;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;

final class Day08 extends AbstractPuzzle
{
    /** @var list<list<int>> */
    private array $map = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = array_map(intval(...), str_split($line));
        }

        $pt1 = $pt2 = 0;

        foreach ($this->map as $i => $line) {
            foreach (array_keys($line) as $j) {
                if ($this->isVisible($i, $j)) ++$pt1;
                $pt2 = max($pt2, $this->getScenicScore($i, $j));
            }
        }

        return new Result($pt1, $pt2);
    }

    private function isVisible(int $i, int $j): bool
    {
        $treeSize = $this->map[$i][$j];
        foreach (Directions::CARDINALS as $direction) {
            $newI = $i + $direction[0];
            $newJ = $j + $direction[1];

            while (isset($this->map[$newI][$newJ])) {
                if ($treeSize <= $this->map[$newI][$newJ]) continue 2;

                $newI += $direction[0];
                $newJ += $direction[1];
            }

            // We reached the end of the map without meeting a bigger tree
            return true;
        }

        return false;
    }

    private function getScenicScore(int $i, int $j): int
    {
        $score = 1;
        foreach (Directions::CARDINALS as $name => $direction) {
            $score *= $this->getTreesVisible(
                $name,
                $this->map[$i][$j],
                0,
                $i + $direction[0],
                $j + $direction[1]
            );

            if (0 === $score) return 0;
        }

        return $score;
    }

    private function getTreesVisible(string $direction, int $treeSize, int $count, int $i, int $j): int
    {
        if (!isset($this->map[$i][$j])) return $count;
        ++$count;
        if ($treeSize <= $this->map[$i][$j]) return $count;

        return $this->getTreesVisible(
            $direction,
            $treeSize,
            $count,
            $i + Directions::CARDINALS[$direction][0],
            $j + Directions::CARDINALS[$direction][1],
        );
    }
}
