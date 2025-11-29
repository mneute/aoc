<?php

declare(strict_types=1);

namespace App\Year2022\Day11;

use Ds\Queue;

final class Monkey
{
    public private(set) readonly int $id;

    /** @var Queue<int> */
    private readonly Queue $items;
    private readonly string $operation;
    private readonly int $modulo;
    private readonly int $nextIfTrue;
    private readonly int $nextIfFalse;
    public private(set) int $inspections = 0;

    public function __construct(string $definition)
    {
        preg_match('#Monkey (\d+)#', $definition, $matches);
        $this->id = (int) $matches[1];

        preg_match('#Starting items: (.+)#', $definition, $matches);
        $this->items = new Queue(array_map(intval(...), explode(', ', $matches[1])));

        preg_match('#Operation: new = (.+)#', $definition, $matches);
        $this->operation = $matches[1];

        preg_match('#Test: divisible by (\d+)#', $definition, $matches);
        $this->modulo = (int) $matches[1];

        preg_match('#If true: throw to monkey (\d+)#', $definition, $matches);
        $this->nextIfTrue = (int) $matches[1];

        preg_match('#If false: throw to monkey (\d+)#', $definition, $matches);
        $this->nextIfFalse = (int) $matches[1];
    }

    public function addItem(int $item): void
    {
        $this->items->push($item);
    }

    /**
     * @return \Generator<int, int> key is the next monkey, value is the processed item
     */
    public function processItems(): \Generator
    {
        foreach ($this->items as $item) {
            ++$this->inspections;

            $operation = str_replace('old', (string) $item, $this->operation);
            $item = (int) eval(\sprintf('return %s;', $operation));
            $item = (int) floor($item / 3);

            if (0 === $item % $this->modulo) {
                yield $this->nextIfTrue => $item;
            } else {
                yield $this->nextIfFalse => $item;
            }
        }
    }
}
