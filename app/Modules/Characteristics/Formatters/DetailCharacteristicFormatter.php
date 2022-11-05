<?php

namespace App\Modules\Characteristics\Formatters;

use App\Modules\Characteristics\Entities\Characteristic;
use Formatter\Formatter;

class DetailCharacteristicFormatter extends Formatter
{
    public function __construct()
    {
        $this->setFormatter(function (Characteristic $characteristic): array
        {
            return [
                'id' => $characteristic->getId(),
                'name' => $characteristic->getName(),
                'description' => $characteristic->getDescription(),
                'minimum' => $characteristic->getMinimum(),
                'maximum' => $characteristic->getMaximum(),
            ];
        });
    }
}
