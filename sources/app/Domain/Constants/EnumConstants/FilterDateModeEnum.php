<?php

namespace App\Domain\Constants\EnumConstants;

enum FilterDateModeEnum: string
{
    case AFTER = 'after';
    case BEFORE = 'before';
}
