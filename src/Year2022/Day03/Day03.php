<?php

declare(strict_types=1);

namespace App\Year2022\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    private const int UPPERCASE_A = 65;
    private const int UPPERCASE_Z = 90;
    private const int LOWERCASE_A = 97;
    private const int LOWERCASE_Z = 122;

    public function run(): Result
    {
        $pt1 = $pt2 = 0;
        $currentGroup = [];

        foreach ($this->readFile() as $i => $line) {
            $length = \strlen($line);
            if (0 !== $length % 2) throw new \RuntimeException(\sprintf('Line %d has an odd number of characters', $i));
            $halfLength = (int) ($length / 2);
            \assert($halfLength > 0);

            [$left, $right] = str_split($line, $halfLength);

            $uniqueLeft = array_unique(str_split($left));
            $uniqueRight = array_unique(str_split($right));

            $intersect = array_values(array_intersect($uniqueLeft, $uniqueRight));
            if (1 !== \count($intersect)) throw new \RuntimeException(\sprintf('Line %d should have exactly one character in common for both parts', $i));

            $priority = $this->getPriority($intersect[0]);

            $pt1 += $priority;

            $currentGroup[$i % 3] = $line;

            if (2 === $i % 3) {
                $pt2 += $this->getGroupPriority($currentGroup);
            }
        }

        return new Result($pt1, $pt2);
    }

    private function getPriority(string $character): int
    {
        $priority = \ord($character);
        if (self::UPPERCASE_A <= $priority && $priority <= self::UPPERCASE_Z) {
            $priority -= (self::UPPERCASE_A - 27);
        } elseif (self::LOWERCASE_A <= $priority && $priority <= self::LOWERCASE_Z) {
            $priority -= (self::LOWERCASE_A - 1);
        } else {
            throw new \RuntimeException(\sprintf('Unknown character %s', $character));
        }

        return $priority;
    }

    /**
     * @param non-empty-array<int<0, 2>, string> $group
     */
    private function getGroupPriority(array $group): int
    {
        if (3 !== \count($group)) throw new \InvalidArgumentException('The group should have a size of 3 elements');

        $intersect = array_values(array_intersect(
            array_unique(str_split($group[0])),
            array_unique(str_split($group[1])),
            array_unique(str_split($group[2])),
        ));
        if (1 !== \count($intersect)) throw new \InvalidArgumentException(\sprintf('The group "%s" should have exactly one character in common for all members', implode('", "', $group)));

        return $this->getPriority($intersect[0]);
    }
}
