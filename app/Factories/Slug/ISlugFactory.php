<?php

namespace App\Factories\Slug;

interface ISlugFactory
{
    public function create(array $params): string;
}
