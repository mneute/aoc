<?php

declare(strict_types=1);

require_once __DIR__.'/Card.php';

$startTime = microtime(true);
memory_reset_peak_usage();

$pt1 = $pt2 = 0;

$file = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES) ?: throw new RuntimeException('Unable to open file!');

$cards = [];

foreach ($file as $index => $line) {
    preg_match('#^Card\s+(?<id>\d+):(?<winning>(?: +\d+)+) \|(?<drawn>(?: +\d+)+)$#', $line, $matches);

    $winning = preg_replace('#\s{2,}#', ' ', trim($matches['winning']));
    $drawn = preg_replace('#\s{2,}#', ' ', trim($matches['drawn']));

    $winningNumbers = array_map(intval(...), explode(' ', $winning));
    $drawnNumbers = array_map(intval(...), explode(' ', $drawn));

    $intersection = count(array_intersect($winningNumbers, $drawnNumbers));

    $pt1 += (int) (2 ** ($intersection - 1));

    $id = (int) $matches['id'];
    $cards[$id] ??= new Card($id);
    $cards[$id]->originalCount = 1;

    if (0 === $intersection) continue;

    foreach (range(1, $intersection) as $item) {
        $nextCard = $id + $item;
        $cards[$nextCard] ??= new Card($nextCard);
        $cards[$nextCard]->copyCount += $cards[$id]->getCount();
    }
}

$pt2 = array_reduce(
    $cards,
    fn (int $carry, Card $card): int => $carry + $card->getCount(),
    0
);

printf('Part 1 : %d'.PHP_EOL, $pt1);
printf('Part 2 : %d'.PHP_EOL.PHP_EOL, $pt2);

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.PHP_EOL.PHP_EOL, round(memory_get_peak_usage() / (2 ** 20), 4));
