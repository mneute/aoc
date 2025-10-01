<?php

declare(strict_types=1);

namespace App\Year2022\Day04;

use App\AbstractPuzzle;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    private const string REGEX = '#^(?<p11>\d+)-(?<p12>\d+),(?<p21>\d+)-(?<p22>\d+)$#';

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            preg_match(self::REGEX, $line, $matches);
            [
                'p11' => $p1_1,
                'p12' => $p1_2,
                'p21' => $p2_1,
                'p22' => $p2_2,
            ] = $matches;

            $group1 = new Group((int) $p1_1, (int) $p1_2);
            $group2 = new Group((int) $p2_1, (int) $p2_2);

            if ($group1->contains($group2) || $group2->contains($group1)) {
                $pt1++;
            }
            if ($group1->overlaps($group2)) {
                $pt2++;
            }
        }

        return new Result($pt1, $pt2);
    }
}
