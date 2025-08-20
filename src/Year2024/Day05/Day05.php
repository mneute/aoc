<?php

declare(strict_types=1);

namespace App\Year2024\Day05;

use App\AbstractPuzzle;
use App\Result;

final class Day05 extends AbstractPuzzle
{
    private static array $pageOrderingRules = [];
    private static array $requiredUpdates = [];

    public function run(): Result
    {
        $this->parseFile();

        $validUpdates = $invalidUpdates = [];
        foreach (self::$requiredUpdates as $list) {
            if ($this->isValidUpdate($list)) {
                $validUpdates[] = $list;
            } else {
                $invalidUpdates[] = $list;
            }
        }

        foreach ($invalidUpdates as &$update) {
            usort($update, static function (int $a, int $b): int {
                if (in_array($b, self::$pageOrderingRules[$a] ?? [], true)) {
                    return -1;
                }
                if (in_array($a, self::$pageOrderingRules[$b] ?? [], true)) {
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
        foreach ($this->readFile(__DIR__.'/input.txt') as $line) {
            if ('' === $line) {
                $hasMetEmtpyLine = true;
                continue;
            }

            if (!$hasMetEmtpyLine) {
                [$key, $value] = array_map(intval(...), explode('|', $line));

                if (!in_array($value, self::$pageOrderingRules[$key] ?? [], true)) {
                    self::$pageOrderingRules[$key][] = $value;
                }
            } else {
                self::$requiredUpdates[] = array_map(intval(...), explode(',', $line));
            }
        }
    }

    /**
     * @param int[] $update
     */
    private function isValidUpdate(array $update): bool
    {
        foreach ($update as $index => $pageNumber) {
            $mustBeAfter = $this->mustBeAfter($pageNumber);

            $lastIndex = count($update) - 1;

            if ($index === $lastIndex) {
                continue;
            }

            foreach (range($index + 1, $lastIndex) as $lookAfter) {
                if (in_array($update[$lookAfter], $mustBeAfter, true)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function mustBeAfter(int $pageNumber): array
    {
        $mustBeAfter = [];
        foreach (self::$pageOrderingRules as $before => $afters) {
            if (in_array($pageNumber, $afters, true)) {
                $mustBeAfter[] = $before;
            }
        }

        return $mustBeAfter;
    }

    /**
     * @param array<array<int>> $lists
     * @return array<int>
     */
    private function getMiddlePages(array $lists): array
    {
        return array_map(
            static fn (array $list): int => $list[floor(count($list) / 2)],
            $lists
        );
    }
}
