<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

const NEEDLE = 't';

$links = [];
$pt1 = 0;

foreach (file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    [$a, $b] = explode('-', $line);

    $links[$a][] = $b;
    $links[$b][] = $a;

    foreach ($links[$a] as $c) {
        if ($b === $c) continue;

        if (in_array($c, $links[$b], true) && (str_starts_with($a, NEEDLE) || str_starts_with($b, NEEDLE) || str_starts_with($c, NEEDLE))) $pt1++;
    }
}

//$interconnections = [];
//foreach ($links as $a => $linkA) {
//    $network = [$a];
//    foreach ($linkA as $b) {
//        if (array_all($network, static fn (string $c): bool => in_array($c, $links[$b], true))) {
//            $network[] = $b;
//        }
//    }
//
//    $interconnections[$a] = $network;
//}

printf('Part 1 : %d'.PHP_EOL, $pt1);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));
