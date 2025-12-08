<?php

declare(strict_types=1);

namespace App\Year2025\Day07;

final class Splitter implements \Stringable
{
    public const string STRING_TEMPLATE = '%d # %d';
    public ?self $leftChild = null;
    public ?self $rightChild = null;
    private int $paths;

    public function __construct(
        public private(set) readonly int $x,
        public private(set) readonly int $y,
    ) {
    }

    public function __toString(): string
    {
        return \sprintf(self::STRING_TEMPLATE, $this->x, $this->y);
    }

    public function countPaths(): int
    {
        return $this->paths ??= ($this->leftChild?->countPaths() ?? 1) + ($this->rightChild?->countPaths() ?? 1);
    }
}
