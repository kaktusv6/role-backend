<?php

namespace App\Modules\Characteristics\Entities;

use App\Modules\Games\Entities\Game;
use Carbon\Carbon;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "characteristics")]
#[UniqueConstraint(name: "characteristics_slug_unique", columns: ["slug"])]
class Characteristic
{
    #[Id, Column(type: "integer"), GeneratedValue()]
    private int $id;

    #[Column(type: "integer")]
    private int $gameId;

    #[ManyToOne(targetEntity: Game::class, inversedBy: 'characteristics')]
    #[JoinColumn(name: 'game_id', referencedColumnName: 'id')]
    private ?Game $game;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "string")]
    private string $slug;

    #[Column(type: "string", nullable: true)]
    private ?string $description;

    #[Column(type: "boolean")]
    private bool $withSign = false;

    #[Column(type: "integer", nullable: true)]
    private ?int $minimum;

    #[Column(type: "integer", nullable: true)]
    private ?int $maximum;

    #[Column(name: "created_at", type: "carbon")]
    private Carbon $createdAt;

    #[Column(name: "updated_at", type: "carbon")]
    private Carbon $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isWithSign(): bool
    {
        return $this->withSign;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
