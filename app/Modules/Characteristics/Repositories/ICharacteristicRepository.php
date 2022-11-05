<?php

namespace App\Modules\Characteristics\Repositories;

use App\Modules\Characteristics\Entities\Characteristic;

interface ICharacteristicRepository
{
    public function getListByGameId(int $gameId): iterable;

    public function getDetailByAlias(string $alias): ?Characteristic;
}
