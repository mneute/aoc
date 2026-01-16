<?php

declare(strict_types=1);

namespace App\Year2021\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    public function run(): Result
    {
        /** @var array<int, array{int, int}> $columns */
        $columns = [];
        foreach ($this->readFile() as $line) {
            if (1 !== preg_match('#^[01]+$#', $line)) throw new \RuntimeException(\sprintf('Invalid line : %s', $line));

            foreach (str_split($line) as $i => $char) {
                $value = (int) $char;
                $columns[$i][$value] ??= 0;
                ++$columns[$i][$value];
            }
        }

        $mostCommonChars = $leastCommonChars = '';
        foreach ($columns as $detail) {
            if ($detail[0] > $detail[1]) {
                $mostCommonChars .= '0';
                $leastCommonChars .= '1';
            } elseif ($detail[0] < $detail[1]) {
                $mostCommonChars .= '1';
                $leastCommonChars .= '0';
            } else {
                throw new \RuntimeException(\sprintf('Both values are equal : %s', var_export($detail, true)));
            }
        }
        $gammaRate = \intval($mostCommonChars, 2);
        $epsilonRate = \intval($leastCommonChars, 2);

        return new Result($gammaRate * $epsilonRate, 0);
    }
}
