<?php

declare(strict_types=1);

namespace App\Year2024\Day11;

use App\AbstractPuzzle;
use App\Result;

final class Day11 extends AbstractPuzzle
{
    /** @var array<string, int> */
    private array $cache = [];

    public function run(): Result
    {
        $stones = $this->getFilePath()
            |> (fn (string $path): string => file_get_contents($path) ?: throw new \RuntimeException('Unable to read file'))
            |> (fn (string $content): array => explode(' ', $content))
            |> (fn (array $list): array => array_map(intval(...), $list));

        $pt1 = $pt2 = 0;

        foreach ($stones as $stone) {
            $pt1 += $this->blink($stone, 25);
            $pt2 += $this->blink($stone, 75);
        }

        return new Result($pt1, $pt2);
    }

    /**
     * @return int how many stones would you get if you transformed \$stone \$blinks times
     */
    private function blink(int $stone, int $blinks): int
    {
        if (0 === $blinks) return 1;

        $key = "$stone-$blinks";
        if (isset($this->cache[$key])) return $this->cache[$key];

        if (0 === $stone) {
            $count = $this->blink(1, $blinks - 1);
        } elseif (0 === \strlen((string) $stone) % 2) {
            $halfLength = \strlen((string) $stone) >> 1;
            \assert($halfLength > 0);

            [$left, $right] = str_split((string) $stone, $halfLength);
            $count = $this->blink((int) $left, $blinks - 1) + $this->blink((int) $right, $blinks - 1);
        } else {
            $count = $this->blink($stone * 2024, $blinks - 1);
        }

        return $this->cache[$key] = $count;
    }
}
