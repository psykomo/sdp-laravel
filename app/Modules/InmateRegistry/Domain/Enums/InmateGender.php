<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Domain\Enums;

enum InmateGender: string
{
    case Male = 'male';
    case Female = 'female';
}
