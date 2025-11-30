<?php

declare(strict_types=1);

namespace App\Year2024\Day15;

use App\AbstractPuzzle;
use App\Result;

final class Day15 extends AbstractPuzzle
{
    /** @var array<int, array<int, string>> */
    private array $map = [];
    private string $moves = '';

    /** @var array{int, int} */
    private array $robotCoordinates;

    public function run(): Result
    {
        $this->parseFile();

        foreach ($this->getNextMove() as $move) {
            $this->moveItems($this->robotCoordinates, $this->getNextCoordinates($this->robotCoordinates, $move), $move);
        }

        return new Result(
            $this->getSumBoxesCoordinates(),
            0
        );
    }

    private function parseFile(): void
    {
        $hasReachedEmptyLine = false;
        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasReachedEmptyLine = true;
                continue;
            }

            if ($hasReachedEmptyLine) {
                $this->moves .= $line;
            } else {
                $this->map[] = str_split($line);
            }
        }

        $this->initCoordinates();
    }

    private function initCoordinates(): void
    {
        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if ('@' === $char) {
                    $this->robotCoordinates = [$i, $j];

                    return;
                }
            }
        }

        throw new \RuntimeException('Unable to find the robot "@" on the map');
    }

    /**
     * @return \Generator<int, string>
     */
    private function getNextMove(): \Generator
    {
        $strlen = \strlen($this->moves);
        for ($i = 0; $i < $strlen; ++$i) {
            yield $this->moves[$i];
        }
    }

    /**
     * @param array{int, int} $curr
     * @param array{int, int} $next
     */
    private function moveItems(array $curr, array $next, string $move): void
    {
        if ('#' === $this->map[$next[0]][$next[1]]) {
            // Nothing to do
            return;
        }
        if ('O' === $this->map[$next[0]][$next[1]]) {
            $this->moveItems($next, $this->getNextCoordinates($next, $move), $move);
        }

        if ('.' === $this->map[$next[0]][$next[1]]) {
            // Swap characters
            [$this->map[$curr[0]][$curr[1]], $this->map[$next[0]][$next[1]]] = [$this->map[$next[0]][$next[1]], $this->map[$curr[0]][$curr[1]]];
        }

        if ('@' === $this->map[$next[0]][$next[1]]) {
            $this->robotCoordinates = $next;
        }
    }

    /**
     * @param array{int, int} $coordinates
     *
     * @return array{int, int}
     */
    private function getNextCoordinates(array $coordinates, string $move): array
    {
        return match ($move) {
            '<' => [$coordinates[0], $coordinates[1] - 1],
            '>' => [$coordinates[0], $coordinates[1] + 1],
            '^' => [$coordinates[0] - 1, $coordinates[1]],
            'v' => [$coordinates[0] + 1, $coordinates[1]],
            default => throw new \InvalidArgumentException(\sprintf('Unknown move "%s"', $move)),
        };
    }

    private function getSumBoxesCoordinates(): int
    {
        $total = 0;
        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if ('O' !== $char) {
                    continue;
                }

                $total += $i * 100 + $j;
            }
        }

        return $total;
    }
}
