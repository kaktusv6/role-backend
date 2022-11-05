<?php

namespace App\Modules\Games\Formatters;

use App\Modules\Characteristics\Formatters\LightCharacteristicFormatter;
use App\Modules\Games\Entities\Game;
use Formatter\Formatter;

class DetailGame extends Formatter
{
    public function __construct(LightCharacteristicFormatter $characteristicFormatter)
    {
        $this->setFormatter(function (Game $game) use ($characteristicFormatter): array
        {
            return [
                'id' => $game->getId(),
                'name' => $game->getName(),
                'description' => $game->getDescription(),
                'characteristics' => $characteristicFormatter->formatList($game->getCharacteristics())
            ];
        });
    }
}
