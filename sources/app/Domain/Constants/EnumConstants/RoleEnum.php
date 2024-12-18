<?php

namespace App\Domain\Constants\EnumConstants;

enum RoleEnum: string
{
    case SUPERUSER = 'superuser';
    case ADMIN = 'admin';
    case ATHLETE = 'athlete';
    case ADMIN_ATHLETE = 'admin.athlete';
}
