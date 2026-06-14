<?php

declare(strict_types=1);

namespace App\Year2020\Day12;

use App\Constants\Directions;

final class ShipWithWaypoint extends Ship
{
    private int $waypointHorizontalPosition = 10;
    private int $waypointVerticalPosition = -1;

    #[\Override]
    public function executeInstruction(string $instruction): void
    {
        $matches = $this->validateInstruction($instruction);

        $letter = $matches[1];
        $value = (int) $matches[2];

        if (\array_key_exists($letter, Directions::CARDINALS)) {
            $this->moveWaypoint($letter, $value);
        } elseif ('F' === $letter) {
            $this->moveTowardsWaypoint($value);
        } else {
            $this->rotateWaypoint($letter, $value);
        }
    }

    private function moveWaypoint(string $letter, int $steps): void
    {
        $direction = Directions::CARDINALS[$letter] ?? throw new \InvalidArgumentException(\sprintf('Invalid direction: %s', $letter));

        $this->waypointVerticalPosition += ($direction[0] * $steps);
        $this->waypointHorizontalPosition += ($direction[1] * $steps);
    }

    private function moveTowardsWaypoint(int $steps): void
    {
        $this->horizontalPosition += ($this->waypointHorizontalPosition * $steps);
        $this->verticalPosition += ($this->waypointVerticalPosition * $steps);
    }

    private function rotateWaypoint(string $letter, int $value): void
    {
        $angle = deg2rad('L' === $letter ? $value : -$value);

        [$y, $x] = [$this->waypointHorizontalPosition, $this->waypointVerticalPosition];
        $xPrime = (int) round($x * cos($angle) - $y * sin($angle));
        $yPrime = (int) round($x * sin($angle) + $y * cos($angle));
        [$this->waypointHorizontalPosition, $this->waypointVerticalPosition] = [$yPrime, $xPrime];
    }
}
