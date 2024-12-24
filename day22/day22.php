<?php

declare(strict_types=1);

memory_reset_peak_usage();
$start_time = microtime(true);

$pt1 = 0;

const PRUNE = 16777216;

$file = fopen(__DIR__ . '/input.txt', 'rb') ?: throw new RuntimeException('Unable to open file!');
while (($line = fgets($file)) !== false) {
    $pt1 += nextSecret((int)$line, 2000);
}
printf('Part 1 : %d' . PHP_EOL, $pt1);

echo sprintf("Execution time: %s seconds" . PHP_EOL, round(microtime(true) - $start_time, 4));
echo sprintf("   Peak memory: %s MiB" . PHP_EOL . PHP_EOL, round(memory_get_peak_usage() / pow(2, 20), 4));


/**
 * @param positive-int $secret
 * @param positive-int $iterations
 * @return positive-int
 */
function nextSecret(int $secret, int $iterations): int
{
    if ($iterations === 0) return $secret;

    $secret = ($secret ^ ($secret << 6)) % PRUNE; // $secret * 64
    $secret = ($secret ^ ($secret >> 5)) % PRUNE; // $secret / 32
    $secret = ($secret ^ ($secret << 11)) % PRUNE; // $secret * 2048
    return nextSecret($secret, $iterations - 1);
}
