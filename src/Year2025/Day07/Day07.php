<?php

declare(strict_types=1);

namespace App\Year2025\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    private const string STARTING_POINT = 'S';
    private const string SPLITTER = '^';

    /** @var list<list<string>> */
    private array $map = [];

    /** @var array<string, Splitter> */
    private array $splitters = [];
    private Splitter $firstSplitter;

    public function run(): Result
    {
        $beams = [];
        foreach ($this->readFile() as $i => $line) {
            if (0 === $i) {
                $startingColumn = strpos($line, self::STARTING_POINT)
                    ?: throw new \RuntimeException('Could not find the starting point');
                $beams = [$startingColumn];
            }

            $this->map[$i] = str_split($line);

            $newBeams = [];
            foreach ($beams as $column) {
                $char = $this->map[$i][$column];

                if (self::SPLITTER !== $char) {
                    if (!\in_array($column, $newBeams, true)) $newBeams[] = $column;
                    continue;
                }

                $splitter = new Splitter($i, $column);
                $this->splitters[(string) $splitter] = $splitter;

                if (!isset($this->firstSplitter)) {
                    $this->firstSplitter = $splitter;
                } else {
                    $this->searchParents($splitter);
                }

                if (!\in_array($column - 1, $newBeams, true)) $newBeams[] = $column - 1;
                if (!\in_array($column + 1, $newBeams, true)) $newBeams[] = $column + 1;
            }
            $beams = $newBeams;
        }

        return new Result(
            \count($this->splitters),
            $this->firstSplitter->countPaths()
        );
    }

    private function searchParents(Splitter $splitter): void
    {
        $i = $splitter->x - 1;
        $j = $splitter->y;
        while (self::SPLITTER !== ($this->map[$i][$j] ?? self::SPLITTER)) {
            if (self::SPLITTER === $this->map[$i][$j - 1]) {
                $leftParent = $this->splitters[\sprintf(Splitter::STRING_TEMPLATE, $i, $j - 1)] ?? null;

                if ($leftParent instanceof Splitter) $leftParent->rightChild = $splitter;
            }
            if (self::SPLITTER === $this->map[$i][$j + 1]) {
                $rightParent = $this->splitters[\sprintf(Splitter::STRING_TEMPLATE, $i, $j + 1)] ?? null;

                if ($rightParent instanceof Splitter) $rightParent->leftChild = $splitter;
            }

            --$i;
        }
    }
}
