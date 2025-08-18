<?php

final class Card
{
    public function __construct(
        public readonly int $id,
        public int $originalCount = 0,
        public int $copyCount = 0,
    ) {
    }

    public function getCount(): int
    {
        if (0 === $this->originalCount) return 0;

        return $this->originalCount + $this->copyCount;
    }
}
