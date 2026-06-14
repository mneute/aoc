<?php

declare(strict_types=1);

namespace App\Year2020\Day12;

use App\AbstractPuzzle;
use App\Result;

final class Day12 extends AbstractPuzzle
{
    public function run(): Result
    {
        $ship = new Ship();
        $shipWithWaypoint = new ShipWithWaypoint();
        foreach ($this->readFile() as $line) {
            $ship->executeInstruction($line);
            $shipWithWaypoint->executeInstruction($line);
        }

        return new Result($ship->manhattanDistance, $shipWithWaypoint->manhattanDistance);
    }
}
