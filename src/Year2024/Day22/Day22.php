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
            $pt1 += $this->nextSecret((int) $line, 2000);
        }

        return new Result($pt1, 0);
    }

    /**
     * @param positive-int $secret
     * @param positive-int $iterations
     *
     * @return positive-int
     */
    private function nextSecret(int $secret, int $iterations): int
    {
        if (0 === $iterations) return $secret;

        $secret = ($secret ^ ($secret << 6)) % self::PRUNE; // $secret * 64
        $secret = ($secret ^ ($secret >> 5)) % self::PRUNE; // $secret / 32
        $secret = ($secret ^ ($secret << 11)) % self::PRUNE; // $secret * 2048

        return $this->nextSecret($secret, $iterations - 1);
    }
}
