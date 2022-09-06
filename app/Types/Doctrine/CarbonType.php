<?php

namespace App\Types\Doctrine;

use Carbon\Doctrine\DateTimeType;

class CarbonType extends DateTimeType
{
    public const NAME = 'carbon';

    public function getName()
    {
        return self::NAME;
    }
}
