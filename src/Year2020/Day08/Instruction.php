<?php

declare(strict_types=1);

namespace App\Year2020\Day08;

final class Instruction
{
    public readonly InstructionTypeEnum $type;
    public readonly int $value;
    public bool $executed = false;

    public function __construct(string $input)
    {
        [$op, $val] = explode(' ', $input);
        $this->type = InstructionTypeEnum::from($op);
        $this->value = (int) $val;
    }
}
