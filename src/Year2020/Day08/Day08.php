<?php

declare(strict_types=1);

namespace App\Year2020\Day08;

use App\AbstractPuzzle;
use App\Result;

final class Day08 extends AbstractPuzzle
{
    public function run(): Result
    {
        /** @var list<Instruction> $instructions */
        $instructions = $this->getFilePath()
                |> (static fn (string $path): array => file($path, \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Unable to read file'))
                |> (static fn (array $lines): array => array_map(static fn (string $line): Instruction => new Instruction($line), $lines));
        $currentLine = $accumulator = 0;
        $instruction = $instructions[$currentLine];

        do {
            $accumulator += InstructionTypeEnum::ACCUMULATOR === $instruction->type ? $instruction->value : 0;
            $currentLine += InstructionTypeEnum::JUMP === $instruction->type ? $instruction->value : 1;
            $instruction->executed = true;

            $instruction = $instructions[$currentLine];
        } while (false === $instruction->executed);

        return new Result($accumulator, 0);
    }
}
