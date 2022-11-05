<?php

namespace App\Modules\Characteristics\Repositories;

use App\Modules\Characteristics\Entities\Characteristic;
use Doctrine\ORM\EntityManagerInterface;

final class CharacteristicRepository implements ICharacteristicRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getListByGameId(int $gameId): iterable
    {
        return $this->entityManager->getRepository(Characteristic::class)->findBy(['game_id' => $gameId]);
    }

    public function getDetailByAlias(string $alias): ?Characteristic
    {
        return $this->entityManager->getRepository(Characteristic::class)->findOneBy(['slug' => $alias]);
    }
}
