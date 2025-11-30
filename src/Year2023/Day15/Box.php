<?php

declare(strict_types=1);

namespace App\Year2023\Day15;

final class Box
{
    /** @var list<Lens> */
    private array $lenses = [];

    public function __construct(
        public private(set) readonly int $id,
    ) {
    }

    public function addLens(string $label, int $focal): void
    {
        $index = $this->getLensIndex($label);
        if (null === $index) {
            $this->lenses[] = new Lens($label, $focal);

            return;
        }

        $this->lenses[$index]->focal = $focal;
    }

    public function removeLens(string $label): void
    {
        $index = $this->getLensIndex($label);
        if (null === $index) return;

        array_splice($this->lenses, $index, 1);
    }

    public function getFocusingPower(): int
    {
        if ([] === $this->lenses) return 0;

        $result = 0;
        foreach ($this->lenses as $position => $lens) {
            $result += ($this->id + 1) * ($position + 1) * $lens->focal;
        }

        return $result;
    }

    private function getLensIndex(string $label): ?int
    {
        return array_find_key(
            $this->lenses,
            static fn (Lens $lens): bool => $lens->label === $label,
        );
    }
}
