<?php

declare(strict_types=1);

namespace App\Year2024\Day06;

use App\AbstractPuzzle;
use App\Result;

final class Day06 extends AbstractPuzzle
{
    private array $map = [];
    private Direction $direction = Direction::UP;
    private array $coordinates = [0, 0];
    private array $lastThreeObstacles = [];
    private bool $isInbounds = true;
    private int $possibleInfiniteLoops = 0;

    public function run(): Result
    {
        $this->parseFile();

        while ($this->isInbounds) {
            if ($this->canMoveForward()) {
                $this->moveForward();
            } else {
                $this->direction = $this->direction->next();

                if ($this->canCreateInfiniteLoop()) {
                    ++$this->possibleInfiniteLoops;
                }
            }
        }

        $pt1 = array_reduce(
            $this->map,
            static fn (int $carry, array $line): int => $carry + count(array_filter($line, static fn (string $char) => 'X' === $char)),
            0
        );

        return new Result($pt1, 0);
    }

    private function parseFile(): void
    {
        foreach ($this->readFile() as $line) {
            $this->map[] = str_split($line);
        }

        $this->initCoordinates();
    }

    private function initCoordinates(): void
    {
        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $char) {
                if ('^' === $char) {
                    $this->coordinates = [$i, $j];

                    return;
                }
            }
        }

        throw new \LogicException('No starting point found');
    }

    private function canMoveForward(): bool
    {
        $newCoordinates = $this->coordinates;
        $this->getNextPosition($this->direction, $newCoordinates);

        if (!isset($this->map[$newCoordinates[0]][$newCoordinates[1]])) {
            // We will be out of bounds meaning we are done, but we can still move forward
            return true;
        }

        if ('#' !== $this->map[$newCoordinates[0]][$newCoordinates[1]]) {
            return true;
        }

        $this->lastThreeObstacles = [
            $newCoordinates,
            ...array_slice($this->lastThreeObstacles, 0, 2),
        ];

        return false;
    }

    private function moveForward(): void
    {
        $newCoordinates = $this->coordinates;
        $this->getNextPosition($this->direction, $newCoordinates);

        $this->map[$this->coordinates[0]][$this->coordinates[1]] = 'X';
        if (!isset($this->map[$newCoordinates[0]][$newCoordinates[1]])) {
            $this->isInbounds = false;
            return;
        }

        $this->coordinates = $newCoordinates;
        $this->map[$this->coordinates[0]][$this->coordinates[1]] = match ($this->direction) {
            Direction::UP => '^',
            Direction::DOWN => 'v',
            Direction::LEFT => '<',
            Direction::RIGHT => '>',
        };
    }

    private function getNextPosition(Direction $direction, array &$coordinates): void
    {
        match ($direction) {
            Direction::UP => $coordinates[0]--,
            Direction::DOWN => $coordinates[0]++,
            Direction::LEFT => $coordinates[1]--,
            Direction::RIGHT => $coordinates[1]++,
        };
    }

    private function canCreateInfiniteLoop(): bool
    {
        if (3 !== count($this->lastThreeObstacles)) {
            return false;
        }

        [$firstEncountered, $lastEncountered] = [$this->lastThreeObstacles[2], $this->lastThreeObstacles[0]];

        $fourthObstacle = match ($this->direction) {
            Direction::UP => [$firstEncountered[0] - 1, $lastEncountered[1] + 1],
            Direction::DOWN => [$firstEncountered[0] + 1, $lastEncountered[1] - 1],
            Direction::LEFT => [$lastEncountered[0] - 1, $firstEncountered[1] - 1],
            Direction::RIGHT => [$lastEncountered[0] + 1, $firstEncountered[1] + 1],
        };

        // TODO Finish this ...

        return false;
    }
}
