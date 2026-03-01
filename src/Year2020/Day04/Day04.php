<?php

declare(strict_types=1);

namespace App\Year2020\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    public function run(): Result
    {
        $part1 = $part2 = 0;
        $currentPassport = '';
        foreach ($this->readFile() as $line) {
            if ('' !== $line) {
                $currentPassport .= '' === $currentPassport ? $line : ' ' . $line;
                continue;
            }

            $passport = new Passport($currentPassport);
            if ($passport->hasAllRequiredFields()) {
                ++$part1;
                if ($passport->isValid()) ++$part2;
            }
            $currentPassport = '';
        }
        $passport = new Passport($currentPassport);
        if ($passport->hasAllRequiredFields()) {
            ++$part1;
            if ($passport->isValid()) ++$part2;
        }

        return new Result($part1, $part2);
    }
}
