<?php

declare(strict_types=1);

namespace App\Year2023\Day15;

use App\AbstractPuzzle;
use App\Result;

final class Day15 extends AbstractPuzzle
{
    private const string INPUT_FILE = __DIR__ . '/input.txt';

    /** @var array<Box> */
    private array $boxes = [];

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

            $label = $matches['label'];
            $operation = $matches['operation'];
            $focal = (int) ($matches['focal'] ?? -1);

            $boxIndex = $this->hashInput($label);
            $this->boxes[$boxIndex] ??= new Box($boxIndex);

            if ('-' === $operation) {
                $this->boxes[$boxIndex]->removeLens($label);
            } else {
                $this->boxes[$boxIndex]->addLens($label, $focal);
            }
        }

        $pt2 = 0;
        foreach ($this->boxes as $box) {
            $pt2 += $box->getFocusingPower();
        }

        return new Result($pt1, $pt2);
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
