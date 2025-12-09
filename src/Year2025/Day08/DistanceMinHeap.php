<?php

declare(strict_types=1);

namespace App\Year2025\Day08;

/**
 * @extends \SplMinHeap<Distance>
 */
final class DistanceMinHeap extends \SplMinHeap
{
    protected function compare($value1, $value2): int
    {
        return $value2->distance <=> $value1->distance;
    }
}
