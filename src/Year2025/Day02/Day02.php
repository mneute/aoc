<?php

declare(strict_types=1);

namespace App\Year2025\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    public function run(): Result
    {
        $content = str_replace(
            "\n",
            '',
            file_get_contents($this->getFilePath()) ?: throw new \RuntimeException('Unreadable file')
        );
        $part1 = $part2 = 0;

        foreach (explode(',', $content) as $range) {
            [$start, $end] = array_map(intval(...), explode('-', $range));

            for ($id = $start; $id <= $end; $id++) {
                if ($this->hasPatternRepeatedTwice($id)) {
                    $part1 += $id;
                    $part2 += $id;
                    continue;
                }

                if ($this->hasRepeatedPattern($id)) $part2 += $id;
            }
        }

        return new Result($part1, $part2);
    }

    private function hasPatternRepeatedTwice(int $input): bool
    {
        $length = strlen((string) $input);
        if (1 === $length % 2) return false;

        return (string) $input === str_repeat(substr((string) $input, 0, $length / 2), 2);
    }

    private function hasRepeatedPattern(int $input): bool
    {
        $stringInput = (string) $input;
        $totalLength = strlen($stringInput);
        $halfLength = (int) round($totalLength / 2, mode: \RoundingMode::HalfTowardsZero);

        for ($currentLength = 1; $currentLength <= $halfLength; $currentLength++) {
            if (0 !== $totalLength % $currentLength) continue;

            $needle = substr($stringInput, 0, $currentLength);
            if ($stringInput === str_repeat($needle, (int) ($totalLength / $currentLength))) return true;
        }

        return false;
    }
}
