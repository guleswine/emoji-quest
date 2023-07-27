<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class SideEnum extends Enum
{
    const Center = 0;
    const Left = 1;
    const Right = 2;
    const Top = 3;
    const Bottom = 4;
    const LeftBottom = 5;
    const LeftTop = 6;
    const RightBottom = 7;
    const RightTop = 8;
}
