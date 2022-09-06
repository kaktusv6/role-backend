<?php

namespace App\Modules\Games\Repositories;

use App\Modules\Games\Entities\Game;

interface IGameRepository
{
    /**
     * @return Game[]
     */
    public function getAll(): iterable;

    public function getDetailByAlias(string $alias): ?Game;
}
