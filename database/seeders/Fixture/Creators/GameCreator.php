<?php

namespace Database\Seeders\Fixture\Creators;

use Illuminate\Support\Facades\DB;

final class GameCreator
{
    private function createSlugFromName(string $name): string
    {
        $slug = strtolower(trim($name));
        return str_replace([' ', ':'], '-', $slug);
    }

    public function createFromDatum(array $datum): int
    {
        return DB::table('games')->insertGetId([
            'name' => $datum['name'],
            'slug' => $this->createSlugFromName($datum['name']),
            'description' => $datum['description'],
        ]);
    }
}
