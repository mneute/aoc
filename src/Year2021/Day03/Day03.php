<?php

declare(strict_types=1);

namespace App\Year2021\Day03;

use App\AbstractPuzzle;
use App\Result;

final class Day03 extends AbstractPuzzle
{
    /** @var list<string> */
    private array $lines;

    public function run(): Result
    {
        $this->lines = file($this->getFilePath(), \FILE_IGNORE_NEW_LINES) ?: throw new \RuntimeException('Unable to read file');

        $length = \strlen($this->lines[0]);
        $gammaRate = $epsilonRate = '';

        for ($i = 0; $i < $length; ++$i) {
            if (0 === $this->getMostCommonBit($i)) {
                $gammaRate .= '0';
                $epsilonRate .= '1';
            } else {
                $gammaRate .= '1';
                $epsilonRate .= '0';
            }
        }

        $oxygenGeneratorRating = $this->getRating($this->lines, true);
        $co2ScrubberRating = $this->getRating($this->lines, false);

        return new Result(
            \intval($gammaRate, 2) * \intval($epsilonRate, 2),
            $oxygenGeneratorRating * $co2ScrubberRating
        );
    }

    /**
     * @return int<0, 1>
     */
    private function getMostCommonBit(int $column): int
    {
        $count0 = $count1 = 0;
        foreach ($this->lines as $line) {
            if ('0' === $line[$column]) {
                ++$count0;
            } else {
                ++$count1;
            }
        }

        if ($count0 === $count1) throw new \RuntimeException(\sprintf('Column %d has the same amount of 0 and 1', $column));

        return $count0 > $count1 ? 0 : 1;
    }

    /**
     * @param list<string> $list
     */
    private function getRating(array $list, bool $mostCommon, int $column = 0): int
    {
        /** @var array<int, list<string>> $splittedList */
        $splittedList = [];

        if (1 === \count($list)) return \intval($list[0], 2);

        foreach ($list as $value) {
            $index = '0' === $value[$column] ? 0 : 1;
            $splittedList[$index][] = $value;
        }

        if ($mostCommon) {
            return \count($splittedList[0]) > \count($splittedList[1])
                ? $this->getRating($splittedList[0], true, $column + 1)
                : $this->getRating($splittedList[1], true, $column + 1);
        }

        return \count($splittedList[0]) <= \count($splittedList[1])
            ? $this->getRating($splittedList[0], false, $column + 1)
            : $this->getRating($splittedList[1], false, $column + 1);
    }
}
