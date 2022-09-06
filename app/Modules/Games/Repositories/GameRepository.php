<?php

namespace App\Modules\Games\Repositories;

use App\Modules\Games\Entities\Game;
use Doctrine\ORM\EntityManagerInterface;

class GameRepository implements IGameRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll(): iterable
    {
        return $this->entityManager->getRepository(Game::class)->findAll();
    }

    public function getDetailByAlias(string $alias): ?Game
    {
        return $this->entityManager->getRepository(Game::class)->findOneBy(['slug' => $alias]);
    }
}
