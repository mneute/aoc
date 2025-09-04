<?php

declare(strict_types=1);

namespace App\Year2023\Day19;

use App\AbstractPuzzle;
use App\Result;

final class Day19 extends AbstractPuzzle
{
    private const string WORKFLOWS = '#^(?<name>[a-z]+)\{(?<detail>.+)\}#';

    /** @var array<string, Workflow> */
    private array $workflows = [];

    public function run(): Result
    {
        $pt1 = 0;

        $hasMetEmptyLine = false;
        foreach ($this->readFile() as $line) {
            if ('' === $line) {
                $hasMetEmptyLine = true;
                continue;
            }

            if (!$hasMetEmptyLine) {
                preg_match(self::WORKFLOWS, $line, $matches);
                $this->workflows[$matches['name']] = new Workflow($matches['detail']);

                continue;
            }

            $part = new Part($line);
            if ('A' === $this->getStatus($part)) {
                $pt1 += $part->getTotal();
            }
        }

        return new Result($pt1, 0);
    }

    private function getStatus(Part $part): string
    {
        $status = 'in';

        while (!in_array($status, ['A', 'R'], true)) {
            $status = $this->workflows[$status]->run($part);
        }

        return $status;
    }
}
