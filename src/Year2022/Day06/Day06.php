<?php

declare(strict_types=1);

namespace App\Year2022\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    public function run(): Result
    {
        $input = trim(file_get_contents($this->getFilePath()));

        $pt1 = $this->getFirstMarker($input, 4);
        $pt2 = $this->getFirstMarker($input, 14);

        return new Result($pt1, $pt2);
    }

    private function getFirstMarker(string $input, int $chunkSize): int
    {
        $length = \strlen($input);

        for ($i = 0; $i < $length - $chunkSize; ++$i) {
            $chunk = substr($input, $i, $chunkSize);

            if ($chunkSize === \count(array_unique(str_split($chunk)))) {
                return $i + $chunkSize;
            }
        }

        throw new \RuntimeException('No marker found');
    }
}
