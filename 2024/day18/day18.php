<?php

use Ds\Queue;

const MAX_VALUE = 70;
const MAX_FALLEN_BYTES = 1024;
const FILE_NAME = __DIR__ . '/input.txt';
$map = [];

parseFile();
printMap();

printf('Plus court chemin : %d'.PHP_EOL, dijkstra());

function parseFile(): void
{
    global $map;

    $map = array_fill(
        0,
        MAX_VALUE + 1,
        array_fill(0, MAX_VALUE + 1, '.')
    );

    $file = fopen(FILE_NAME, 'rb') ?: throw new RuntimeException('Unable to open file!');
    $lineCount = 0;
    while (($line = fgets($file)) !== false && $lineCount++ < MAX_FALLEN_BYTES) {
        $line = trim($line);

        [$x, $y] = array_map(intval(...), explode(',', $line));

        $map[$y][$x] = '#';
    }
}

function printMap(): void
{
    global $map;

    foreach ($map as $line) {
        echo implode('', $line) . PHP_EOL;
    }
}

function dijkstra(): int
{
    global $map;

    $start = [0, 0];
    $end = [MAX_VALUE, MAX_VALUE];

    $queue = new Queue();
    $queue->push([$start, 0]);

    $directions = [
        [0, 1],  // v
        [0, -1], // ^
        [1, 0],  // >
        [-1, 0], // <
    ];

    $visited = [];

    foreach ($queue as [$position, $steps]) {
        if ($position === $end) return $steps;

        foreach ($directions as $direction) {
            [$x, $y] = [$position[0] + $direction[0], $position[1] + $direction[1]];

            if ($x < 0 || $y < 0 || $x > MAX_VALUE || $y > MAX_VALUE) continue;

            if ($map[$y][$x] === '#') continue;

            $key = "$x-$y";
            if ($visited[$key] ?? false) continue;

            $visited[$key] = true;
            $queue->push([[$x, $y], $steps + 1]);
        }
    }

    return -1;
}
