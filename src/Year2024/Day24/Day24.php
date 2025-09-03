<?php

declare(strict_types=1);

namespace App\Year2024\Day24;

use App\AbstractPuzzle;
use App\Result;

final class Day24 extends AbstractPuzzle
{
    private array $knownStates = [];
    private array $logicGates = [];

    public function run(): Result
    {
        $hasMetEmptyLine = false;

        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasMetEmptyLine = true;
                continue;
            }

            if (!$hasMetEmptyLine) {
                [$wire, $value] = explode(': ', $line);
                $this->knownStates[$wire] = (int) $value;
            } else {
                [$input, $output] = explode(' -> ', $line);
                $this->logicGates[$output] = $input;
            }
        }

        $z = array_filter($this->logicGates, static fn (string $key): bool => str_starts_with($key, 'z'), \ARRAY_FILTER_USE_KEY);
        krsort($z);
        $pt1 = array_map($this->evalLogicGate(...), array_keys($z), $z);

        return new Result(
            bindec(implode('', $pt1)),
            0
        );
    }

    /**
     * @return int<0,1>
     */
    private function evalLogicGate(string $wire, string $input): int
    {
        if (isset($this->knownStates[$wire])) return $this->knownStates[$wire];

        [$a, $operation, $b] = explode(' ', $input);

        $valA = $this->knownStates[$a] ?? $this->evalLogicGate($a, $this->logicGates[$a]);
        $valB = $this->knownStates[$b] ?? $this->evalLogicGate($b, $this->logicGates[$b]);

        return $this->knownStates[$wire] = match ($operation) {
            'AND' => $valA & $valB,
            'OR' => $valA | $valB,
            'XOR' => $valA ^ $valB,
            default => throw new \RuntimeException(sprintf('Unmanaged operation : %s', $operation))
        };
    }
}
