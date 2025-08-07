<?php

$map = [];
$countAntinodes = 0;

parseFile();
printMap();

function parseFile(): void
{
    global $map;

    $file = fopen(__DIR__.'/input-test.txt', 'rb') ?: throw new RuntimeException('Unable to open file!');

    while (($line = fgets($file)) !== false) {
        $map[] = str_split(trim($line));
    }
}

function printMap(): void
{
    global $map;

    foreach ($map as $line) {
        echo implode('', $line).PHP_EOL;
    }
}
