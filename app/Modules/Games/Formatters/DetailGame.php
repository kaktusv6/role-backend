<?php

namespace App\Modules\Games\Formatters;

use App\Modules\Games\Entities\Game;
use Formatter\Formatter;

class DetailGame extends Formatter
{
    public function __construct()
    {
        $this->setFormatter(function (Game $game): array
        {
            return [
                'id' => $game->getId(),
                'name' => $game->getName(),
                'description' => $game->getDescription(),
            ];
        });
    }
}
