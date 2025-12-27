<?php

declare(strict_types=1);

namespace App\Year2024\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    /** @var array<int, array<int, int>> */
    private array $pageOrderingRules = [];

    /** @var list<list<int>> */
    private array $requiredUpdates = [];

    public function run(): Result
    {
        $this->parseFile();

        $validUpdates = $invalidUpdates = [];
        foreach ($this->requiredUpdates as $list) {
            if ($this->isValidUpdate($list)) {
                $validUpdates[] = $list;
            } else {
                $invalidUpdates[] = $list;
            }
        }

        foreach ($invalidUpdates as &$update) {
            usort($update, function (int $a, int $b): int {
                if (\in_array($b, $this->pageOrderingRules[$a] ?? [], true)) {
                    return -1;
                }
                if (\in_array($a, $this->pageOrderingRules[$b] ?? [], true)) {
                    return 1;
                }

                return 0;
            });
        }

        $validMiddlePages = $this->getMiddlePages($validUpdates);
        $invalidMiddlePages = $this->getMiddlePages($invalidUpdates);

        return new Result(
            array_sum($validMiddlePages),
            array_sum($invalidMiddlePages),
        );
    }

    private function parseFile(): void
    {
        $hasMetEmtpyLine = false;
        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasMetEmtpyLine = true;
                continue;
            }

            if (!$hasMetEmtpyLine) {
                [$key, $value] = array_map(intval(...), explode('|', $line));

                if (!\in_array($value, $this->pageOrderingRules[$key] ?? [], true)) {
                    $this->pageOrderingRules[$key][] = $value;
                }
            } else {
                $this->requiredUpdates[] = array_map(intval(...), explode(',', $line));
            }
        }
    }

    /**
     * @param int[] $update
     */
    private function isValidUpdate(array $update): bool
    {
        $lastIndex = \count($update) - 1;

        foreach ($update as $index => $pageNumber) {
            $mustBeAfter = $this->mustBeAfter($pageNumber);

            if ($index === $lastIndex) {
                continue;
            }

            foreach (range($index + 1, $lastIndex) as $lookAfter) {
                if (\in_array($update[$lookAfter], $mustBeAfter, true)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return list<int>
     */
    private function mustBeAfter(int $pageNumber): array
    {
        $mustBeAfter = [];
        foreach ($this->pageOrderingRules as $before => $afters) {
            if (\in_array($pageNumber, $afters, true)) {
                $mustBeAfter[] = $before;
            }
        }

        return $mustBeAfter;
    }

    /**
     * @param array<array<int>> $lists
     *
     * @return array<int>
     */
    private function getMiddlePages(array $lists): array
    {
        return array_map(
            static fn (array $list): int => $list[(int) floor(\count($list) / 2)],
            $lists
        );
    }
}
