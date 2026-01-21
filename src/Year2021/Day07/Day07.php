<?php

declare(strict_types=1);

namespace App\Year2021\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    public function run(): Result
    {
        $positions = $this->getFilePath()
                |> (fn (string $path): string => file_get_contents($path) ?: throw new \RuntimeException('Unable to read file'))
                |> (fn (string $content): array => explode(',', $content))
                |> (fn (array $list): array => array_map(intval(...), $list));

        $start = min($positions);
        $end = max($positions);

        $part1 = $part2 = \PHP_INT_MAX;

        foreach (range($start, $end) as $meetingPoint) {
            $score1 = $score2 = 0;

            foreach ($positions as $position) {
                $distance = abs($meetingPoint - $position);

                $score1 += $distance;
                $score2 += (int) ($distance * ($distance + 1) / 2);
            }

            $part1 = min($score1, $part1);
            $part2 = min($score2, $part2);
        }

        return new Result($part1, $part2);
    }
}
