<?php

namespace App\Domain\Constants\EnumConstants;

enum ClientRoleNameEnum: string
{
    case ADMIN = 'Администратор';
    case ATHLETE = 'Спортсмен';
}
