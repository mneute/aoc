<?php

declare(strict_types=1);

namespace App\Year2024\Day25;

use App\AbstractPuzzle;
use App\Result;

final class Day25 extends AbstractPuzzle
{
    private array $locks = [];
    private array $keys = [];

    public function run(): Result
    {
        $this->parseFile();

        $pt1 = 0;
        foreach ($this->locks as $lock) {
            foreach ($this->keys as $key) {
                if (array_all(range(0, 4), static fn (int $column): bool => $lock[$column] + $key[$column] <= 5)) $pt1++;
            }
        }

        return new Result($pt1, 0);
    }

    private function parseFile(): void
    {
        /** @var ?Type $type */
        $type = null;

        foreach ($this->readFile(__DIR__.'/input.txt') as $i => $line) {
            if (0 === $i % 8) {
                if (str_starts_with($line, '.')) {
                    $type = Type::KEY;
                    $this->keys[] = array_fill(0, 5, 0);
                } else {
                    $type = Type::LOCK;
                    $this->locks[] = array_fill(0, 5, 0);
                }

                continue;
            }
            if (in_array($i % 8, [6, 7], true)) continue;

            if ($type === Type::KEY) {
                $row = count($this->keys) - 1;
                foreach (str_split($line) as $column => $char) {
                    $this->keys[$row][$column] += $char === '#' ? 1 : 0;
                }
            } else {
                $row = count($this->locks) - 1;
                foreach (str_split($line) as $column => $char) {
                    $this->locks[$row][$column] += $char === '#' ? 1 : 0;
                }
            }
        }
    }
}
