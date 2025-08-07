<?php

declare(strict_types=1);

enum Type
{
    case LOCK;
    case KEY;
}

$startTime = microtime(true);
memory_reset_peak_usage();

$locks = [];
$keys = [];
$pt1 = 0;

parseFile();

//echo "LOCKS".PHP_EOL;
//foreach ($locks as $lock) {
//    echo implode(',', $lock).PHP_EOL;
//}
//echo "KEYS".PHP_EOL;
//foreach ($keys as $key) {
//    echo implode(',', $key).PHP_EOL;
//}
//echo PHP_EOL;

foreach ($locks as $lock) {
    foreach ($keys as $key) {
        if (array_all(range(0, 4), static fn (int $column): bool => $lock[$column] + $key[$column] <= 5)) $pt1++;
    }
}

printf('Part 1 : %d'.PHP_EOL, $pt1);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));

function parseFile(): void
{
    global $locks, $keys;

    /** @var ?Type $type */
    $type = null;

    foreach (file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) as $i => $line) {
        if (0 === $i % 8) {
            if (str_starts_with($line, '.')) {
                $type = Type::KEY;
                $keys[] = array_fill(0, 5, 0);
            } else {
                $type = Type::LOCK;
                $locks[] = array_fill(0, 5, 0);
            }

            continue;
        }
        if (in_array($i % 8, [6, 7], true)) continue;

        if ($type === Type::KEY) {
            $row = count($keys) - 1;
            foreach (str_split($line) as $column => $char) {
                $keys[$row][$column] += $char === '#' ? 1 : 0;
            }
        } else {
            $row = count($locks) - 1;
            foreach (str_split($line) as $column => $char) {
                $locks[$row][$column] += $char === '#' ? 1 : 0;
            }
        }
    }
}
