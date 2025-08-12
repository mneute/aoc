<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

const MAX_RED = 12;
const MAX_GREEN = 13;
const MAX_BLUE = 14;

$file = fopen(__DIR__.'/input.txt', 'rb') ?: throw new RuntimeException('Unable to open file!');

while (false !== ($line = fgets($file))) {
    $line = trim($line);

    $minRed = $minGreen = $minBlue = 1;

    preg_match('#^Game (?<id>\d+): (?<input>.+)$#', $line, $gameMatches);

    $input = $gameMatches['input'];
    $id = (int) $gameMatches['id'];

    foreach (explode(';', $input) as $round) {
        foreach (explode(',', $round) as $cubes) {
            $cubes = trim($cubes);

            preg_match('#^(?<count>\d+) (?<color>red|blue|green)$#', $cubes, $cubeMatches);

            $count = (int) $cubeMatches['count'];
            $color = $cubeMatches['color'];

            if ('red' === $color) {
                $max = MAX_RED;
                $minRed = max($minRed, $count);
            } elseif ('green' === $color) {
                $max = MAX_GREEN;
                $minGreen = max($minGreen, $count);
            } elseif ('blue' === $color) {
                $max = MAX_BLUE;
                $minBlue = max($minBlue, $count);
            } else {
                throw new RuntimeException(sprintf('Unknown color : %s', $color));
            }

            if ($max < $count) {
                $id = 0; // Part 1 is impossible, nothing to add
            }
        }
    }

    $pt1 += $id;
    $pt2 += ($minRed * $minGreen * $minBlue);
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.PHP_EOL.PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));
