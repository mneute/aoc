<?php

declare(strict_types=1);

namespace App\Year2022\Day08;

use App\AbstractPuzzle;
use App\Result;

final class Day08 extends AbstractPuzzle
{
    private const array DIRECTIONS = [
        'North' => [-1, 0],
        'West' => [0, 1],
        'South' => [1, 0],
        'East' => [0, -1],
    ];

    /** @var list<list<int>> */
    private array $map = [];

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = array_map(intval(...), str_split($line));
        }

        $pt1 = $pt2 = 0;

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $value) {
                if ($this->isVisible($value, $i, $j)) $pt1++;
            }
        }

        return new Result($pt1, $pt2);
    }

    private function isVisible(int $value, int $i, int $j): bool
    {
        foreach (self::DIRECTIONS as $direction) {
            $newI = $i + $direction[0];
            $newJ = $j + $direction[1];

            // Edge of the map, tree is visible
            if (!isset($this->map[$newI][$newJ])) return true;

            while (isset($this->map[$newI][$newJ])) {
                
            }
        }
    }
}
