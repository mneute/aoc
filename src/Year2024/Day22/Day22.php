<?php

declare(strict_types=1);

namespace App\Year2024\Day22;

use App\AbstractPuzzle;
use App\Result;

final class Day22 extends AbstractPuzzle
{
    private const int PRUNE = 16777216;

    public function run(): Result
    {
        $pt1 = 0;
        foreach ($this->readFile() as $line) {
            \assert((int) $line >= 0);

            $pt1 += $this->nextSecret((int) $line, 2000);
        }

        return new Result($pt1, 0);
    }

    /**
     * @param int<0, max> $secret
     * @param int<0, max> $iterations
     *
     * @return int<0, max>
     */
    private function nextSecret(int $secret, int $iterations): int
    {
        if (0 === $iterations) return $secret;

        $secret = ($secret ^ ($secret << 6)) % self::PRUNE; // $secret * 64
        $secret = ($secret ^ ($secret >> 5)) % self::PRUNE; // $secret / 32
        $secret = ($secret ^ ($secret << 11)) % self::PRUNE; // $secret * 2048

        \assert($secret >= 0);

        return $this->nextSecret($secret, $iterations - 1);
    }
}
