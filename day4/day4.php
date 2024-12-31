<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$input = array_map(str_split(...), file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES));

$pt1 = $pt2 = 0;

for ($i = 0; $i < count($input); $i++) {
    for ($j = 0; $j < count($input[$i]); $j++) {
        if ('X' === $input[$i][$j]) {
            $pt1 += tryPart1($i, $j);
        }
    }
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));

function tryPart1(int $i, int $j): int
{
    global $input;

    $maxI = count($input);
    $maxJ = count($input[0]);
    $steps = 3;

    $directions = [
        [0, 1], // ➡
        [1, 1], // ↘
        [1, 0], // ⬇
        [1, -1], // ↙
        [0, -1], // ⬅
        [-1, -1], // ↖
        [-1, 0], // ⬆
        [-1, 1], // ↗
    ];

    $count = 0;

    foreach ($directions as $direction) {
        // bounds check
        $end = [
            $i + ($direction[0] * $steps),
            $j + ($direction[1] * $steps),
        ];

        if ($end[0] < 0 || $end[0] >= $maxI || $end[1] < 0 || $end[1] >= $maxJ) continue;

        if (tryDirectionPart1($i, $direction[0], $j, $direction[1])) $count++;
    }

    return $count;
}

function tryDirectionPart1(int $i, int $stepI, int $j, int $stepJ): bool
{
    global $input;

    $i += $stepI;
    $j += $stepJ;

    foreach (['M', 'A', 'S'] as $letter) {
        if ($letter !== $input[$i][$j]) {
            return false;
        }

        $i += $stepI;
        $j += $stepJ;
    }

    return true;
}
