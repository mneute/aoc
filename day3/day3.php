<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

$mulRegex = 'mul\((\d+),(\d+)\)';

$isOn = true;
foreach (file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    preg_match_all(sprintf('/%s/', $mulRegex), $line, $matchesPart1);

    foreach ($matchesPart1[1] as $i => $a) {
        $b = $matchesPart1[2][$i];

        $pt1 += ($a * $b);
    }

    preg_match_all(sprintf('/do(?:n\'t)?\(\)|%s/', $mulRegex), $line, $matchesPart2);

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

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));
