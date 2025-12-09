<?php

declare(strict_types=1);

namespace App\Year2025\Day08;

use Ds\Vector;

final class Circuit implements \Stringable
{
    private static int $instances = 0;
    private readonly int $id;

    /** @var Vector<Point> */
    private readonly Vector $points;

    public function __construct()
    {
        $this->id = ++self::$instances;
        $this->points = new Vector();
    }

    public function __toString(): string
    {
        return \sprintf('Circuit #%d', $this->id);
    }

    public function size(): int
    {
        return $this->points->count();
    }

    public function contains(Point $point): bool
    {
        return $this->points->contains($point);
    }

    public function add(Point ...$points): self
    {
        $this->points->push(...$points);

        return $this;
    }

    public function merge(self $other): self
    {
        foreach ($other->points as $point) {
            if (!$this->contains($point)) $this->add($point);
        }

        return $this;
    }
}
