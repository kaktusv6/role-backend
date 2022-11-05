<?php

namespace Database\Seeders\Fixture\Creators;

use App\Factories\Slug\DefaultSlugFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class CharacteristicCreator extends Creator
{
    public function create(int $gameId, array $datum): void
    {
        $now = Carbon::now();
        /** @var DefaultSlugFactory $slugCreator */
        $slugCreator = app(DefaultSlugFactory::class);

        DB::table('characteristics')->insert([
            'game_id' => $gameId,
            'name' => $datum['name'],
            'slug' => $slugCreator->create([
                $this->getNextIdTable('characteristics'),
                $datum['name'],
            ]),
            'description' => $datum['description'] ?? null,
            'with_sign' => $datum['with_sign'] ?? false,
            'minimum' => $datum['minimum'] ?? null,
            'maximum' => $datum['maximum'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
