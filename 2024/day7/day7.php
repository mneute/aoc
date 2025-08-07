<?php

declare(strict_types=1);

$file = fopen(__DIR__.'/input.txt', 'rb') ?: throw new \RuntimeException('Could not open file');

$pt1 = $pt2 = 0;

while (($line = fgets($file)) !== false) {
    $data = array_map(intval(...), explode(' ', trim($line)));

    $expectedResult = array_shift($data);

    if (isPossible($expectedResult, $data)) {
        $pt1 += $expectedResult;
        $pt2 += $expectedResult;
    } elseif (isPossible($expectedResult, $data, true)) {
        $pt2 += $expectedResult;
    }
}

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL, $pt2);

/**
 * @param int[] $data
 */
function isPossible(int $expectedResult, array $data, bool $pt2 = false): bool
{
    if (count($data) === 1) return $expectedResult === $data[0];

    $n1 = array_shift($data);
    $n2 = array_shift($data);

    if ($n1 > $expectedResult) return false;

    return isPossible($expectedResult, [$n1 + $n2, ...$data], $pt2)
        || isPossible($expectedResult, [$n1 * $n2, ...$data], $pt2)
        || ($pt2 && isPossible($expectedResult, [(int) ($n1.$n2), ...$data], $pt2));
}
