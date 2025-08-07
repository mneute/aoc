<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

$pt1Chars = array_map(strval(...), range(1, 9));
$pt2Chars = [...$pt1Chars, 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

$file = fopen(__DIR__.'/input.txt', 'rb') ?: throw new RuntimeException('Unable to open file!');

while (false !== ($line = fgets($file))) {
    $line = trim($line);

    $firstNumberPt1 = getFirstOccurence($line, $pt1Chars);
    $lastNumberPt1 = getLastOccurence($line, $pt1Chars);
    $numberPt1 = (int) ($firstNumberPt1.$lastNumberPt1);

    $pt1 += $numberPt1;

    $firstNumberPt2 = getFirstOccurence($line, $pt2Chars);
    $lastNumberPt2 = getLastOccurence($line, $pt2Chars);
    $numberPt2 = (int) ($firstNumberPt2.$lastNumberPt2);

    $pt2 += $numberPt2;
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));


function getFirstOccurence(string $input, array $needles): int
{
    $min = PHP_INT_MAX;
    $value = -1;

    foreach ($needles as $index => $needle) {
        $pos = strpos($input, $needle);
        if (false === $pos) continue;

        if ($pos < $min) {
            $min = $pos;
            $value = ($index % 9) + 1; // Because of the structure of the array, "1" becomes 1, "one" becomes 1, "2" becomes 2 etc.
        }
    }

    return $value;
}

function getLastOccurence(string $input, array $needles): int
{
    $max = $value = -1;

    foreach ($needles as $index => $needle) {
        $pos = strrpos($input, $needle);
        if (false === $pos) continue;

        if ($pos > $max) {
            $max = $pos;
            $value = ($index % 9) + 1; // Because of the structure of the array, "1" becomes 1, "one" becomes 1, "2" becomes 2 etc.
        }
    }

    return $value;
}
