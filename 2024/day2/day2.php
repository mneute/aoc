<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

foreach (file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    $data = explode(' ', $line);

    if (isSafe($data)) {
        $pt1++;
        $pt2++;

        continue;
    }

    foreach (range(0, count($data) - 1) as $i) {
        $copy = $data;
        array_splice($copy, $i, 1);

        if (isSafe($copy)) {
            $pt2++;

            continue 2;
        }
    }
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.PHP_EOL.PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));

function isSafe(array $data): bool
{
    $max = count($data) - 2;
    $steps = [];
    for ($i = 0; $i <= $max; $i++) {
        $steps[] = $data[$i + 1] - $data[$i];
    }

    return array_all($steps, static fn (int $step): bool => 1 <= $step && $step <= 3)
        || array_all($steps, static fn (int $step): bool => -3 <= $step && $step <= -1);
}
