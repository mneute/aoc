<?php

declare(strict_types=1);

const WORD = 'XMAS';
$lettersToLookFor = strlen(WORD) - 1;

$letters = [];

function parseFile(array &$letters): void
{
    $file = @fopen(__DIR__.'/input.txt', 'rb');
    if (!$file) {
        throw new \RuntimeException('Could not open the file');
    }

    while (($line = fgets($file)) !== false) {
        $line = trim($line);

        $letters[] = str_split($line);
    }
}

function tryPosition(int $i, int $j): int
{
    global $letters, $lettersToLookFor, $maxI, $maxJ;

    $count = 0;

    // ➡
    if (($j + $lettersToLookFor) < $maxJ && tryDirection($i, 0, $j, 1)) {
        $count++;
    }
    // ↘
    if (($j + $lettersToLookFor) < $maxJ && ($i + $lettersToLookFor) < $maxI && tryDirection($i, 1, $j, 1)) {
        $count++;
    }
    // ⬇
    if (($i + $lettersToLookFor) < $maxI && tryDirection($i, 1, $j, 0)) {
        $count++;
    }
    // ↙
    if (($j - $lettersToLookFor) >= 0 && ($i + $lettersToLookFor) < $maxI && tryDirection($i, 1, $j, -1)) {
        $count++;
    }
    // ⬅
    if (($j - $lettersToLookFor) >= 0 && tryDirection($i, 0, $j, -1)) {
        $count++;
    }
    // ↖
    if (($j - $lettersToLookFor) >= 0 && ($i - $lettersToLookFor) >= 0 && tryDirection($i, -1, $j, -1)) {
        $count++;
    }
    // ⬆
    if (($i - $lettersToLookFor) >= 0 && tryDirection($i, -1, $j, 0)) {
        $count++;
    }
    // ↗
    if (($j + $lettersToLookFor) < $maxJ && ($i - $lettersToLookFor) >= 0 && tryDirection($i, -1, $j, 1)) {
        $count++;
    }

    return $count;
}

function tryDirection(int $i, int $stepI, int $j, int $stepJ): bool
{
    global $letters, $lettersToLookFor;

    $i += $stepI;
    $j += $stepJ;

    foreach (range(1, $lettersToLookFor) as $index) {
        if (WORD[$index] !== $letters[$i][$j]) {
            return false;
        }

        $i += $stepI;
        $j += $stepJ;
    }

    return true;
}

parseFile($letters);

$maxI = count($letters);
$maxJ = count($letters[0]);

$count = 0;

for ($i = 0; $i < count($letters); $i++) {
    for ($j = 0; $j < count($letters[$i]); $j++) {
        if (WORD[0] === $letters[$i][$j]) {
            $count += tryPosition($i, $j);
        }
    }
}

printf('X : %d'.PHP_EOL, $count);
