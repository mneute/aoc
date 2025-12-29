<?php

declare(strict_types=1);

namespace App\Year2024\Day04;

use App\AbstractPuzzle;
use App\Constants\Directions;
use App\Result;

final class Day04 extends AbstractPuzzle
{
    /** @var list<list<string>> */
    private array $input;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;

        foreach ($this->readFile() as $line) {
            $this->input[] = str_split($line);
        }

        foreach ($this->input as $i => $line) {
            foreach ($line as $j => $char) {
                if ('X' === $char) {
                    $pt1 += $this->tryPart1($i, $j);
                } elseif ('A' === $char) {
                    $pt2 += $this->tryPart2($i, $j);
                }
            }
        }

        return new Result($pt1, $pt2);
    }

    private function tryPart1(int $i, int $j): int
    {
        $count = 0;

        foreach (Directions::ALL as $direction) {
            [$i2, $j2] = [$i, $j];
            foreach (['M', 'A', 'S'] as $letter) {
                $i2 += $direction[0];
                $j2 += $direction[1];

                if ($letter !== ($this->input[$i2][$j2] ?? null)) continue 2;
            }

            ++$count;
        }

        return $count;
    }

    private function tryPart2(int $i, int $j): int
    {
        $corners = '';
        foreach (Directions::INTERCARDINALS as $direction) {
            $i2 = $i + $direction[0];
            $j2 = $j + $direction[1];

            $corners .= $this->input[$i2][$j2] ?? '.';
        }

        return (int) \in_array($corners, ['MMSS', 'MSSM', 'SSMM', 'SMMS'], true);
    }
}
