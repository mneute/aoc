<?php

declare(strict_types=1);

namespace App\Year2020\Day12;

use App\Constants\Directions;

class Ship
{
    /** @var list<string> */
    protected readonly array $cardinals;
    protected string $facing = 'E';
    protected int $horizontalPosition = 0;
    protected int $verticalPosition = 0;
    public int $manhattanDistance {
        get => abs($this->horizontalPosition) + abs($this->verticalPosition);
    }

    public function __construct()
    {
        $this->cardinals = array_keys(Directions::CARDINALS);
    }

    public function executeInstruction(string $instruction): void
    {
        $matches = $this->validateInstruction($instruction);

        $letter = $matches[1];
        $value = (int) $matches[2];

        if (\array_key_exists($letter, Directions::CARDINALS)) {
            $this->move($letter, $value);
        } elseif ('F' === $letter) {
            $this->move($this->facing, $value);
        } else {
            $this->rotate($letter, $value);
        }
    }

    /**
     * @return array{string, string, numeric-string}
     */
    protected function validateInstruction(string $instruction): array
    {
        if (1 !== preg_match('/^([NSEWLRF])(\d+)$/', $instruction, $matches)) {
            throw new \InvalidArgumentException(\sprintf('Invalid instruction: %s', $instruction));
        }

        return $matches;
    }

    private function move(string $letter, int $steps): void
    {
        $direction = Directions::CARDINALS[$letter] ?? throw new \InvalidArgumentException(\sprintf('Invalid direction: %s', $letter));

        $this->verticalPosition += ($direction[0] * $steps);
        $this->horizontalPosition += ($direction[1] * $steps);
    }

    private function rotate(string $direction, int $value): void
    {
        if (0 !== $value % 90) {
            throw new \InvalidArgumentException(\sprintf('Invalid value : %d', $value));
        }
        $steps = (int) ($value / 90);
        $steps *= ('L' === $direction) ? -1 : 1;
        $cardinal = array_find_key($this->cardinals, fn (string $v): bool => $v === $this->facing);
        $nextCardinal = (($cardinal + $steps) % 4 + 4) % 4;

        $this->facing = $this->cardinals[$nextCardinal];
    }
}
