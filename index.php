<?php

use App\AbstractPuzzle;

require_once './vendor/autoload.php';

if (3 !== count($argv)) {
    throw new InvalidArgumentException(sprintf('Usage : php %s <year> <day>', __FILE__));
}

$year = $argv[1];
$day = str_pad($argv[2], 2, '0', STR_PAD_LEFT);

$className = sprintf('App\\Year%1$s\\Day%2$s\\Day%2$s', $year, $day);

if (!class_exists($className)) {
    throw new InvalidArgumentException(sprintf('Class %s does not exist', $className));
}

$instance = new $className();
if (!$instance instanceof AbstractPuzzle) {
    throw new LogicException(sprintf('%s should be an instance of %s', $className, AbstractPuzzle::class));
}

$startTime = microtime(true);
memory_reset_peak_usage();

$result = $instance->run();

printf('Part 1 : %d'.PHP_EOL, $result->part1);
printf('Part 2 : %d'.PHP_EOL.PHP_EOL, $result->part2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.PHP_EOL.PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));

