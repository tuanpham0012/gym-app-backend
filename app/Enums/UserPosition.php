<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserPosition extends Enum
{
    const User = 0;
    const Member = 1;
    const Coach = 2;
    const Admin = 3;
}
