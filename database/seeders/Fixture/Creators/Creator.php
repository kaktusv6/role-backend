<?php

namespace Database\Seeders\Fixture\Creators;

use Illuminate\Support\Facades\DB;

abstract class Creator
{
    public function getNextIdTable(string $tableName): int
    {
        return (DB::selectOne("select max(id) as id from {$tableName}")->id ?? 0) + 1;
    }
}
