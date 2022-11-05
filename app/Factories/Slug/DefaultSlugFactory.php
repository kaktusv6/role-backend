<?php

namespace App\Factories\Slug;

use Behat\Transliterator\Transliterator;

class DefaultSlugFactory implements ISlugFactory
{
    public const SEPARATOR = '-';

    protected function createSlugFromString(string $value): string
    {
        return str_replace(' ', static::SEPARATOR, Transliterator::transliterate($value));
    }

    public function create(array $params): string
    {
        foreach ($params as $key => $param)
        {
            $params[$key] = $this->createSlugFromString($param);
        }

        return implode(static::SEPARATOR, $params);
    }
}
