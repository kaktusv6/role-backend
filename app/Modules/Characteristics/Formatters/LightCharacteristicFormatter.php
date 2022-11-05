<?php

namespace App\Modules\Characteristics\Formatters;

use App\Modules\Characteristics\Entities\Characteristic;
use Formatter\Formatter;

class LightCharacteristicFormatter extends Formatter
{
    public function __construct()
    {
        $this->setFormatter(function (Characteristic $characteristic): array
        {
            return [
                'id' => $characteristic->getId(),
                'alias' => $characteristic->getSlug(),
                'title' => $characteristic->getName(),
            ];
        });
    }
}
