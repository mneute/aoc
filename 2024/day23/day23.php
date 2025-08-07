<?php

declare(strict_types=1);

use Ds\Set;

$startTime = microtime(true);
memory_reset_peak_usage();

const NEEDLE = 't';

/** @var Set[] $links */
$links = [];
$networks = [];
$pt1 = 0;

foreach (file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    [$a, $b] = explode('-', $line);

    ($links[$a] ??= new Set())->add($b);
    ($links[$b] ??= new Set())->add($a);

    foreach ($links[$a] as $c) {
        if ($b === $c) continue;

        if ($links[$b]->contains($c)) {
            $networks[] = [$a, $b, $c];

            if (str_starts_with($a, NEEDLE) || str_starts_with($b, NEEDLE) || str_starts_with($c, NEEDLE)) $pt1++;
        }
    }
}

printf('Part 1 : %d' . PHP_EOL, $pt1);

printf('Execution time : %s seconds' . PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib' . PHP_EOL . PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));
