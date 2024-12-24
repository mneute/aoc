<?php

declare(strict_types=1);

$pageOrderingRules = [];
$requiredUpdates = [];

function parseFile(): void
{
    global $pageOrderingRules, $requiredUpdates;

    $file = @fopen(__DIR__ . '/input.txt', 'rb');

    if (!$file) {
        throw new \RuntimeException('Could not open file');
    }

    $hasMetEmtpyLine = false;
    while (($line = fgets($file)) !== false) {
        $line = trim($line);

        if ('' === $line) {
            $hasMetEmtpyLine = true;
            continue;
        }

        if (!$hasMetEmtpyLine) {
            [$key, $value] = array_map('intval', explode('|', $line));

            if (!in_array($value, $pageOrderingRules[$key] ?? [], true)) {
                $pageOrderingRules[$key][] = $value;
            }
        } else {
            $requiredUpdates[] = array_map('intval', explode(',', $line));
        }
    }
}

/**
 * @param int[] $update
 */
function isValidUpdate(array $update): bool
{
    global $pageOrderingRules, $requiredUpdates;

    foreach ($update as $index => $pageNumber) {
        $mustBeAfter = mustBeAfter($pageNumber);

        $lastIndex = count($update) - 1;

        if ($index === $lastIndex) {
            continue;
        }

        foreach (range($index + 1, $lastIndex) as $lookAfter) {
            if (in_array($update[$lookAfter], $mustBeAfter, true)) {
                return false;
            }
        }
    }

    return true;
}

function mustBeAfter(int $pageNumber): array
{
    global $pageOrderingRules;

    $mustBeAfter = [];
    foreach ($pageOrderingRules as $before => $afters) {
        if (in_array($pageNumber, $afters, true)) {
            $mustBeAfter[] = $before;
        }
    }

    return $mustBeAfter;
}

parseFile();

$invalidUpdates = array_filter($requiredUpdates, static fn(array $list): bool => !isValidUpdate($list));

foreach ($invalidUpdates as &$update) {
    usort($update, static function (int $a, int $b) use ($pageOrderingRules): int {
        if (in_array($b, $pageOrderingRules[$a] ?? [], true)) {
            return -1;
        }
        if (in_array($a, $pageOrderingRules[$b] ?? [], true)) {
            return 1;
        }

        return 0;
    });
}

$middlePages = array_map(
    static fn(array $list): int => $list[floor(count($list) / 2)],
    $invalidUpdates
);

printf('Somme : %d' . PHP_EOL, array_sum($middlePages));
