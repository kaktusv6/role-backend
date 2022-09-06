<?php

namespace App\Modules\Games\Formatters;

use App\Modules\Games\Entities\Game;
use Formatter\Formatter;

class LightGame extends Formatter
{
    public function __construct()
    {
        $this->setFormatter(function (Game $game): array
        {
            return [
                'alias' => $game->getSlug(),
                'name' => $game->getName(),
            ];
        });
    }
}
