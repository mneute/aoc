<?php

declare(strict_types=1);

namespace App\Year2022\Day02;

use App\AbstractPuzzle;
use App\Result;

final class Day02 extends AbstractPuzzle
{
    private const string REGEX = '#^(?<theirs>[ABC])\s+(?<ours>[XYZ])$#';

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            preg_match(self::REGEX, $line, $matches);

            $theirs = Shape::create($matches['theirs']);
            $ours = Shape::create($matches['ours']);

            $pt1 += ($ours->value + $ours->compare($theirs));

            $guess = Shape::guess($theirs, $matches['ours']);
            $pt2 += ($guess->value + $guess->compare($theirs));
        }

        return new Result($pt1, $pt2);
    }
}
