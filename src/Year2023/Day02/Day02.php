<?php

declare(strict_types=1);

namespace App\Year2023\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    private const int MAX_RED = 12;
    private const int MAX_GREEN = 13;
    private const int MAX_BLUE = 14;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $minRed = $minGreen = $minBlue = 1;

            preg_match('#^Game (?<id>\d+): (?<input>.+)$#', $line, $gameMatches);
            \assert(\array_key_exists('id', $gameMatches) && \array_key_exists('input', $gameMatches));

            $input = $gameMatches['input'];
            $id = (int) $gameMatches['id'];

            foreach (explode(';', $input) as $round) {
                foreach (explode(',', $round) as $cubes) {
                    $cubes = trim($cubes);

                    preg_match('#^(?<count>\d+) (?<color>red|blue|green)$#', $cubes, $cubeMatches);
                    \assert(\array_key_exists('count', $cubeMatches) && \array_key_exists('color', $cubeMatches));

                    $count = (int) $cubeMatches['count'];
                    $color = $cubeMatches['color'];

                    if ('red' === $color) {
                        $max = self::MAX_RED;
                        $minRed = max($minRed, $count);
                    } elseif ('green' === $color) {
                        $max = self::MAX_GREEN;
                        $minGreen = max($minGreen, $count);
                    } elseif ('blue' === $color) {
                        $max = self::MAX_BLUE;
                        $minBlue = max($minBlue, $count);
                    } else {
                        throw new \RuntimeException(\sprintf('Unknown color : %s', $color));
                    }

                    if ($max < $count) {
                        $id = 0; // Part 1 is impossible, nothing to add
                    }
                }
            }

            $pt1 += $id;
            $pt2 += ($minRed * $minGreen * $minBlue);
        }

        return new Result($pt1, $pt2);
    }
}
