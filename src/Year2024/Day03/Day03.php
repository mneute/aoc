<?php

declare(strict_types=1);

namespace App\Year2024\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    private const string REGEX = 'mul\((\d+),(\d+)\)';

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        $isOn = true;
        foreach ($this->readFile(__DIR__.'/input.txt') as $line) {
            preg_match_all(sprintf('/%s/', self::REGEX), $line, $matchesPart1);

            foreach ($matchesPart1[1] as $i => $a) {
                $b = $matchesPart1[2][$i];

                $pt1 += ($a * $b);
            }

            preg_match_all(sprintf('/do(?:n\'t)?\(\)|%s/', self::REGEX), $line, $matchesPart2);

            foreach ($matchesPart2[0] as $i => $instruction) {
                if ($instruction === 'do()') {
                    $isOn = true;
                    continue;
                }
                if ($instruction === "don't()") {
                    $isOn = false;
                    continue;
                }

                if ($isOn) {
                    $pt2 += ($matchesPart2[1][$i] * $matchesPart2[2][$i]);
                }
            }
        }

        return new Result($pt1, $pt2);
    }
}
