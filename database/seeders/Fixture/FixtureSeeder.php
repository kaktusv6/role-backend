<?php

namespace Database\Seeders\Fixture;

use Database\Seeders\Fixture\Creators\CharacteristicCreator;
use Database\Seeders\Fixture\Creators\GameCreator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Utils\JsonUtils;

final class FixtureSeeder extends Seeder
{
    private const PATH_TO_FIXTURE = __DIR__ . '/fixtures.json';

    private const EXCLUDE_TABLETS = [
        'migrations',
    ];

    private function getTabletNames(): array
    {
        $result = DB::select("
            SELECT
                table_name
            FROM
                information_schema.tables
            WHERE
                table_type = 'BASE TABLE'
                AND table_schema NOT IN ('pg_catalog', 'information_schema')
                AND table_name NOT IN (:excludeTabletNames);
        ", [
            'excludeTabletNames' => implode(',', self::EXCLUDE_TABLETS),
        ]);

        return array_column($result, 'table_name');
    }

    private function disableForeignKeys(array $tabletNames): void
    {
        foreach ($tabletNames as $name) {
            DB::statement("ALTER TABLE $name DISABLE TRIGGER ALL;");
        }
    }

    private function enableForeignKeys(array $tabletNames): void
    {
        foreach ($tabletNames as $name) {
            DB::statement("ALTER TABLE $name ENABLE TRIGGER ALL;");
        }
    }

    private function deleteAllData(): void
    {
        $tabletNames = $this->getTabletNames();
        $this->disableForeignKeys($tabletNames);

        foreach ($tabletNames as $name) {
            DB::statement("TRUNCATE TABLE $name RESTART IDENTITY");
        }

        $this->enableForeignKeys($tabletNames);
    }

    private function insertData(): void
    {
        $gameData = JsonUtils::decodeFile(self::PATH_TO_FIXTURE);

        $gameCreator = new GameCreator();
        $characteristicCreator = new CharacteristicCreator();

        foreach ($gameData as $gameDatum) {
            $gameId = $gameCreator->create($gameDatum);

            foreach ($gameDatum['characteristics'] as $characteristicDatum)
            {
                $characteristicCreator->create($gameId, $characteristicDatum);
            }
        }
    }

    public function run()
    {
        $this->deleteAllData();
        $this->insertData();
    }
}
