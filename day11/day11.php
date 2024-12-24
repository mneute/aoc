<?php

$startTime = microtime(true);
memory_reset_peak_usage();

$stones = array_map(intval(...), explode(' ', trim(file_get_contents(__DIR__.'/input.txt'))));

$cache = [];
$pt1 = $pt2 = 0;

foreach ($stones as $stone) {
    $pt1 += blink($stone, 25);
    $pt2 += blink($stone, 75);
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.PHP_EOL.PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));

/**
 * @param int<0, max> $stone
 * @param int<0, max> $blinks
 * @return int<0, max> How many stones would you get if you transformed \$stone \$blinks times.
 */
function blink(int $stone, int $blinks): int
{
    global $cache;

    if ($blinks === 0) return 1;

    $key = "$stone-$blinks";
    if (isset($cache[$key])) return $cache[$key];

    if (0 === $stone) {
        $count = blink(1, $blinks - 1);
    } elseif (0 === strlen($stone) % 2) {
        [$left, $right] = str_split($stone, strlen($stone) / 2);
        $count = blink($left, $blinks - 1) + blink($right, $blinks - 1);
    } else {
        $count = blink($stone * 2024, $blinks - 1);
    }

    return $cache[$key] = $count;
}
