<?php

declare(strict_types=1);

namespace App\Year2021\Day10;

use App\AbstractPuzzle;
use App\Result;
use Ds\Stack;

final class Day10 extends AbstractPuzzle
{
    private const array OPENING_CHARS = ['(', '[', '{', '<'];
    private const array CLOSING_CHARS = [')', ']', '}', '>'];
    private const array SCORES_IF_INVALID = [3, 57, 1197, 25137];

    public function run(): Result
    {
        $part1 = 0;
        $scoresPart2 = [];

        foreach ($this->readFile() as $line) {
            /** @var Stack<string> $stack */
            $stack = new Stack();

            foreach (str_split($line) as $char) {
                if (\in_array($char, self::OPENING_CHARS, true)) {
                    $stack->push($char);
                    continue;
                }

                $index = array_find_key(self::CLOSING_CHARS, static fn (string $c): bool => $c === $char);
                \assert(\is_int($index));

                if ($stack->pop() === self::OPENING_CHARS[$index]) continue;

                $part1 += self::SCORES_IF_INVALID[$index];
                continue 2;
            }

            if ($stack->isEmpty()) continue;

            $score = 0;
            foreach ($stack as $char) {
                $score *= 5;

                $index = array_find_key(self::OPENING_CHARS, static fn (string $c): bool => $c === $char);
                \assert(\is_int($index));

                $score += 1 + $index;
            }
            $scoresPart2[] = $score;
        }

        sort($scoresPart2);
        $index = (int) (\count($scoresPart2) / 2);
        $part2 = $scoresPart2[$index];

        return new Result($part1, $part2);
    }
}
