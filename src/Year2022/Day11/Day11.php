<?php

declare(strict_types=1);

namespace App\Year2022\Day11;

use App\AbstractPuzzle;
use App\Result;

final class Day11 extends AbstractPuzzle
{
    public function run(): Result
    {
        $monkeys = [];

        $content = file_get_contents($this->getFilePath());
        foreach (explode("\n\n", $content) as $monkeyDefinition) {
            $monkey = new Monkey($monkeyDefinition);
            $monkeys[$monkey->id] = $monkey;
        }

        for ($i = 0; $i < 20; $i++) {
            foreach ($monkeys as $monkey) {
                foreach ($monkey->processItems() as $nextMonkey => $item) {
                    $monkeys[$nextMonkey]->addItem($item);
                }
            }
        }
        usort($monkeys, fn (Monkey $a, Monkey $b): int => $b->inspections <=> $a->inspections);

        return new Result(
            $monkeys[0]->inspections * $monkeys[1]->inspections,
            0
        );
    }
}
