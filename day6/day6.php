<?php

declare(strict_types=1);

require_once 'Direction.php';

$map = [];
$direction = Direction::UP;
$coordinates = [0, 0];
$lastThreeObstacles = [];
$isInbounds = true;
$possibleInfiniteLoops = 0;

function parseFile(): void
{
    global $map;

    $file = fopen(__DIR__.'/input-test.txt', 'rb');
    if (!$file) {
        throw new \RuntimeException('Could not open file');
    }

    while (($line = fgets($file)) !== false) {
        $map[] = str_split(trim($line));
    }

    initCoordinates();
}

function initCoordinates(): void
{
    global $map, $coordinates;

    foreach ($map as $i => $line) {
        foreach ($line as $j => $char) {
            if ('^' === $char) {
                $coordinates = [$i, $j];

                return;
            }
        }
    }

    throw new \LogicException('No starting point found');
}

function printMap(): void
{
    global $map;
    foreach ($map as $line) {
        echo implode('', $line).PHP_EOL;
    }
}

function getNextPosition(Direction $direction, array &$coordinates): void
{
    match ($direction) {
        Direction::UP => $coordinates[0]--,
        Direction::DOWN => $coordinates[0]++,
        Direction::LEFT => $coordinates[1]--,
        Direction::RIGHT => $coordinates[1]++,
    };
}

function canMoveForward(): bool
{
    global $map, $coordinates, $direction, $lastThreeObstacles;

    $newCoordinates = $coordinates;
    getNextPosition($direction, $newCoordinates);

    if (!isset($map[$newCoordinates[0]][$newCoordinates[1]])) {
        // We will be out of bounds meaning we are done but we can still move forward
        return true;
    }

    if ('#' !== $map[$newCoordinates[0]][$newCoordinates[1]]) {
        return true;
    }

    $lastThreeObstacles = [
        $newCoordinates,
        ...array_slice($lastThreeObstacles, 0, 2),
    ];

    return false;
}

function moveForward(): void
{
    global $map, $coordinates, $direction, $isInbounds;

    $newCoordinates = $coordinates;
    getNextPosition($direction, $newCoordinates);

    $map[$coordinates[0]][$coordinates[1]] = 'X';
    if (!isset($map[$newCoordinates[0]][$newCoordinates[1]])) {
        $isInbounds = false;
        return;
    }

    $coordinates = $newCoordinates;
    $map[$coordinates[0]][$coordinates[1]] = match ($direction) {
        Direction::UP => '^',
        Direction::DOWN => 'v',
        Direction::LEFT => '<',
        Direction::RIGHT => '>',
    };
}

function canCreateInfiniteLoop(): bool
{
    global $lastThreeObstacles, $direction, $coordinates;

    if (3 !== count($lastThreeObstacles)) {
        return false;
    }

    [$firstEncountered, $lastEncountered] = [$lastThreeObstacles[2], $lastThreeObstacles[0]];

    $fourthObstacle = match ($direction) {
        Direction::UP => [$firstEncountered[0] - 1, $lastEncountered[1] + 1],
        Direction::DOWN => [$firstEncountered[0] + 1, $lastEncountered[1] - 1],
        Direction::LEFT => [$lastEncountered[0] - 1, $firstEncountered[1] - 1],
        Direction::RIGHT => [$lastEncountered[0] + 1, $firstEncountered[1] + 1],
    };

    return false;
}

parseFile();

while ($isInbounds) {
    if (canMoveForward()) {
        moveForward();
    } else {
        $direction = $direction->next();

        if (canCreateInfiniteLoop()) {
            ++$possibleInfiniteLoops;
        }
    }
}
printMap();
$somme = array_reduce(
    $map,
    static fn (int $carry, array $line): int => $carry + count(array_filter($line, static fn (string $char) => 'X' === $char)),
    0
);

printf('Somme                      : %d'.PHP_EOL, $somme);
printf('Boucles infinies possibles : %d'.PHP_EOL, $possibleInfiniteLoops);
