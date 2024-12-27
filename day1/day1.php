<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

$column1 = [];
$column2 = [];

$cachePt2 = [];

foreach (file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    if (1 === preg_match('/^(\d+)\s+(\d+)$/', $line, $matches)) {
        $column1[] = (int) $matches[1];
        $column2[] = (int) $matches[2];
    }
}

sort($column1);
sort($column2);

foreach (range(0, count($column1) - 1) as $i) {
    $pt1 += abs($column1[$i] - $column2[$i]);

    $pt2 += ($cachePt2[$column1[$i]] ??= $column1[$i] * count(array_filter($column2, static fn (int $j): bool => $column1[$i] === $j)));
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));
