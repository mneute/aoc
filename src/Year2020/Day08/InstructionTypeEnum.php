<?php

declare(strict_types=1);

namespace App\Year2020\Day08;

enum InstructionTypeEnum: string
{
    case ACCUMULATOR = 'acc';
    case JUMP = 'jmp';
    case NOOP = 'nop';
}
