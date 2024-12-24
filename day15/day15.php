<?php

$map = [];
$moves = '';
$robotCoordinates = [];

parseFile();

foreach (getNextMove() as $move) {
    moveItems($robotCoordinates, getNextCoordinates($robotCoordinates, $move), $move);
}

printMap();
printf('Somme : %d'.PHP_EOL, getSumBoxesCoordinates());

function parseFile(): void
{
    global $map, $moves;

    $file = fopen(__DIR__.'/input.txt', 'rb') ?: throw new \RuntimeException("Unable to open input file");

    $hasReachedEmptyLine = false;
    while (($line = fgets($file)) !== false) {
        $line = trim($line);

        if ($line === '') {
            $hasReachedEmptyLine = true;
            continue;
        }

        if ($hasReachedEmptyLine) {
            $moves .= $line;
        } else {
            $map[] = str_split($line);
        }
    }

    initCoordinates();
}

function initCoordinates(): void
{
    global $map, $robotCoordinates;

    foreach ($map as $i => $line) {
        foreach ($line as $j => $char) {
            if ($char === '@') {
                $robotCoordinates = [$i, $j];

                return;
            }
        }
    }

    throw new \RuntimeException('Unable to find the robot "@" on the map');
}

function printMap(): void
{
    global $map;

    foreach ($map as $line) {
        echo implode('', $line).PHP_EOL;
    }
}

function getNextMove(): \Generator
{
    global $moves;

    $strlen = strlen($moves);
    for ($i = 0; $i < $strlen; ++$i) {
        yield $moves[$i];
    }
}

function moveItems(array $curr, array $next, string $move): void
{
    global $map, $robotCoordinates;

    if ('#' === $map[$next[0]][$next[1]]) {
        // Nothing to do
        return;
    }
    if ('O' === $map[$next[0]][$next[1]]) {
        moveItems($next, getNextCoordinates($next, $move), $move);
    }

    if ('.' === $map[$next[0]][$next[1]]) {
        // Swap characters
        //printf('swapping %s and %s'.PHP_EOL, implode(':', $curr), implode(':', $next));

        [$map[$curr[0]][$curr[1]], $map[$next[0]][$next[1]]] = [$map[$next[0]][$next[1]], $map[$curr[0]][$curr[1]]];
    }

    if ('@' === $map[$next[0]][$next[1]]) {
        $robotCoordinates = $next;
    }
}

function getNextCoordinates(array $coordinates, string $move): array
{
    return match ($move) {
        '<' => [$coordinates[0], $coordinates[1] - 1],
        '>' => [$coordinates[0], $coordinates[1] + 1],
        '^' => [$coordinates[0] - 1, $coordinates[1]],
        'v' => [$coordinates[0] + 1, $coordinates[1]],
        default => throw new \InvalidArgumentException(sprintf('Unknown move "%s"', $move)),
    };
}

function getSumBoxesCoordinates(): int
{
    global $map;

    $total = 0;
    foreach ($map as $i => $line) {
        foreach ($line as $j => $char) {
            if ($char !== 'O') {
                continue;
            }

            $total += $i * 100 + $j;
        }
    }

    return $total;
}
