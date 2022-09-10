<?php

namespace Database\Seeders;

use Database\Seeders\Fixture\FixtureSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local'))
            $this->call(FixtureSeeder::class);
    }
}
