<?php

declare(strict_types=1);

namespace App\Year2022\Day13;

enum Order
{
    case CORRECT;
    case INCORRECT;
    case NOT_ENOUGH_INFO;
}
