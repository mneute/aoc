<?php

declare(strict_types=1);

namespace App\Year2025\Day08;

use App\AbstractPuzzle;
use App\Result;
use Ds\Vector;

final class Day08 extends AbstractPuzzle
{
    /** @var array<string, Point> */
    private array $points = [];
    private DistanceMinHeap $distances;

    /** @var Vector<Circuit> */
    private Vector $circuits;

    public function run(): Result
    {
        $this->distances = new DistanceMinHeap();
        $this->circuits = new Vector();

        foreach ($this->readFile() as $line) {
            $pointA = new Point($line);

            foreach ($this->points as $pointB) {
                $this->distances->insert(new Distance($pointA, $pointB));
            }
            $this->points[$line] = $pointA;
        }

        $this->createCircuits();

        $part1 = 1;
        foreach ($this->circuits as $index => $circuit) {
            if ($index >= 3) break;
            $part1 *= $circuit->size();
        }

        return new Result($part1, 0);
    }

    private function createCircuits(): void
    {
        $minIndex = $this->distances->count() - ($this->test ? 10 : 1000);

        foreach ($this->distances as $dIndex => $distance) {
            if ($dIndex < $minIndex) break;
            $found = false;
            $firstCircuit = null;
            foreach ($this->circuits as $cIndex => $circuit) {
                if ($circuit->contains($distance->a)) {
                    if ($circuit->contains($distance->b)) continue 2;

                    if ($firstCircuit instanceof Circuit) {
                        $firstCircuit->merge($circuit);
                        $this->circuits->remove($cIndex);
                        continue 2;
                    }

                    $found = true;
                    $firstCircuit = $circuit;
                    $circuit->add($distance->b);
                } elseif ($circuit->contains($distance->b)) {
                    if ($firstCircuit instanceof Circuit) {
                        $firstCircuit->merge($circuit);
                        $this->circuits->remove($cIndex);
                        continue 2;
                    }

                    $found = true;
                    $firstCircuit = $circuit;
                    $circuit->add($distance->a);
                }
            }
            if (!$found) {
                $circuit = new Circuit();
                $circuit->add($distance->a, $distance->b);

                $this->circuits->push($circuit);
            }
        }

        $this->circuits->sort(fn (Circuit $a, Circuit $b): int => $b->size() <=> $a->size());
    }
}
