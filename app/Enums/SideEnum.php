<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class SideEnum extends Enum
{
    const Center = 'center';
    const Left = 'left';
    const Right = 'right';
    const Top = 'top';
    const Bottom = 'bottom';
    const LeftBottom = 'left-bottom';
    const LeftTop = 'left-top';
    const RightBottom = 'right-bottom';
    const RightTop = 'right-top';
}
