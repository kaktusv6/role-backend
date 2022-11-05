<?php

namespace App\Providers;

use App\Modules\Characteristics\Repositories\CharacteristicRepository;
use App\Modules\Characteristics\Repositories\ICharacteristicRepository;
use App\Modules\Games\Repositories\IGameRepository;
use App\Modules\Games\Repositories\GameRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerRepository(): void
    {
        $this->app->bind(IGameRepository::class, GameRepository::class);
        $this->app->bind(ICharacteristicRepository::class, CharacteristicRepository::class);
    }
}
