<?php

namespace App\Providers;

use App\Types\Doctrine\CarbonType;
use Doctrine\DBAL\Types\Type;
use LaravelDoctrine\ORM\DoctrineServiceProvider as BaseServiceProvider;

class DoctrineServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        parent::register();

        Type::addType(CarbonType::NAME, CarbonType::class);
    }
}
