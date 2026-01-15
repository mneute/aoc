<?php

declare(strict_types=1);

namespace App\Year2021\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    private const string REGEX = '#^(?<move>forward|up|down) (?<count>\d+)$#';

    public function run(): Result
    {
        $abscissa = $depthPt1 = $depthPt2 = $aimPt2 = 0;

        foreach ($this->readFile() as $line) {
            if (1 !== preg_match(self::REGEX, $line, $matches)) throw new \RuntimeException(\sprintf('Invalid line : %s', $line));

            $move = $matches['move'];
            $count = (int) $matches['count'];

            if ('forward' === $move) {
                $abscissa += $count;
                $depthPt2 += ($aimPt2 * $count);
            } elseif ('up' === $move) {
                $depthPt1 -= $count;
                $aimPt2 -= $count;
            } else {
                $depthPt1 += $count;
                $aimPt2 += $count;
            }
        }

        return new Result($abscissa * $depthPt1, $abscissa * $depthPt2);
    }
}
