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

        foreach (explode(',', $content) as $rangeInput) {
            $range = new Range($rangeInput);

            $part1 += $this->getSumInvalidIds($range);

            // for ($id = $start; $id <= $end; ++$id) {
            //     if ($this->hasPatternRepeatedTwice($id)) {
            //         $part1 += $id;
            //         $part2 += $id;
            //         continue;
            //     }
            //
            //     if ($this->hasRepeatedPattern($id)) $part2 += $id;
            // }
        }

        return new Result($part1, $part2);
    }

    private function getSumInvalidIds(Range $range): int
    {
        $adjustedStart = $range->start;
        $adjustedEnd = $range->end;

        $sLength = \strlen((string) $adjustedStart);
        if (0 !== $sLength % 2) {
            $adjustedStart = 10 ** $sLength;
            ++$sLength;
        }
        $eLength = \strlen((string) $adjustedEnd);
        if (0 !== $eLength % 2) {
            --$eLength;
            $adjustedEnd = (10 ** $eLength) - 1;
        }
        if ($adjustedStart > $adjustedEnd) return 0;

        $sum = 0;
        $step = $this->getStep($sLength, 2);

        $number = $this->getFirstMultipleOf($adjustedStart, $step);
        while ($number <= $adjustedEnd) {
            $sum += $number;
            $number += $step;
        }

        return $sum;
    }

    private function getStep(int $length, int $countRepeatedPatterns): int
    {
        \assert(0 === $length % $countRepeatedPatterns);

        $pattern = str_pad('1', $length / $countRepeatedPatterns, '0', \STR_PAD_LEFT);

        return (int) str_repeat($pattern, $countRepeatedPatterns);
    }

    private function getFirstMultipleOf(int $number, int $modulo): int
    {
        return 0 === $number % $modulo
            ? $number
            : $number + ($modulo - ($number % $modulo));
    }

    // private function hasRepeatedPattern(int $input): bool
    // {
    //     $stringInput = (string) $input;
    //     $totalLength = \strlen($stringInput);
    //     $halfLength = (int) round($totalLength / 2, mode: \RoundingMode::HalfTowardsZero);
    //
    //     for ($currentLength = 1; $currentLength <= $halfLength; ++$currentLength) {
    //         if (0 !== $totalLength % $currentLength) continue;
    //
    //         $needle = substr($stringInput, 0, $currentLength);
    //         if ($stringInput === str_repeat($needle, (int) ($totalLength / $currentLength))) return true;
    //     }
    //
    //     return false;
    // }
}
