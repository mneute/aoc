<?php

declare(strict_types=1);

namespace App\Year2023\Day15;

use App\AbstractPuzzle;
use App\Result;

final class Day15 extends AbstractPuzzle
{
    private const string INPUT_FILE = __DIR__ . '/input.txt';

    public function run(): Result
    {
        $input = '';
        foreach ($this->readFile(self::INPUT_FILE) as $line) {
            $input .= $line;
        }

        $pt1 = 0;

        $steps = explode(',', $input);
        foreach ($steps as $step) {
            $pt1 += array_reduce(
                str_split($step),
                fn (int $carry, string $char): int => $this->hash($carry, $char),
                0
            );
        }

        return new Result($pt1, 0);
    }

    private function hash(int $currentValue, string $char): int
    {
        return ($currentValue + ord($char)) * 17 % 256;
    }
}
