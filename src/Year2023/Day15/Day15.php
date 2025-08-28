<?php

declare(strict_types=1);

namespace App\Year2023\Day15;

use App\AbstractPuzzle;
use App\Result;

final class Day15 extends AbstractPuzzle
{
    private const string INPUT_FILE = __DIR__ . '/input-test.txt';

    public function run(): Result
    {
        $input = '';
        foreach ($this->readFile(self::INPUT_FILE) as $line) {
            $input .= $line;
        }

        $pt1 = 0;

        $steps = explode(',', $input);
        foreach ($steps as $step) {
            $pt1 += $this->hashInput($step);

            preg_match('#^(?<label>[a-z]+)(?<operation>[=-])(?<focal>\d+)?$#', $step, $matches);

            printf('Label : "%s", operation : "%s"', $matches['label'], $matches['operation']);
            if (isset($matches['focal'])) {
                printf(', focal : %d', $matches['focal']);
            }
            printf(PHP_EOL);
        }

        printf('%s : %d'.PHP_EOL, 'rn=1', $this->hashInput('rn'));

        return new Result($pt1, 0);
    }

    private function hashInput(string $input): int
    {
        return array_reduce(
            str_split($input),
            static fn (int $carry, string $char): int => ($carry + ord($char)) * 17 % 256,
            0
        );
    }
}
