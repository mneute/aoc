<?php

declare(strict_types=1);

namespace App\Year2024\Day23;

use App\AbstractPuzzle;
use App\Result;
use Ds\Set;

final class Day23 extends AbstractPuzzle
{
    private const string NEEDLE = 't';

    public function run(): Result
    {
        /** @var Set[] $links */
        $links = [];
        // $networks = [];
        $pt1 = 0;

        foreach ($this->readFile(__DIR__.'/input.txt') as $line) {
            [$a, $b] = explode('-', $line);

            ($links[$a] ??= new Set())->add($b);
            ($links[$b] ??= new Set())->add($a);

            foreach ($links[$a] as $c) {
                if ($b === $c) continue;

                if ($links[$b]->contains($c)) {
                    // $networks[] = [$a, $b, $c];

                    if (str_starts_with($a, self::NEEDLE) || str_starts_with($b, self::NEEDLE) || str_starts_with($c, self::NEEDLE)) $pt1++;
                }
            }
        }

        return new Result($pt1, 0);
    }
}
