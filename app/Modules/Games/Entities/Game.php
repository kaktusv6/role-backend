<?php

namespace App\Modules\Games\Entities;

use Carbon\Carbon;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "games")]
#[UniqueConstraint(name: "games_slug_unique", columns: ["slug"])]
final class Game
{
    #[Id, Column(type: "integer"), GeneratedValue()]
    private int $id;

    #[Column(type: "string")]
    private string $slug;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "string", nullable: true)]
    private ?string $description;

    #[Column(name: "created_at", type: "carbon")]
    private Carbon $createdAt;

    #[Column(name: "updated_at", type: "carbon")]
    private Carbon $updatedAt;

    #[Column(name: "deleted_at", type: "carbon", nullable: true)]
    private ?Carbon $deletedAt;

    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->slug = str_replace(' ', '-', trim(mb_strtolower($name)));
        $this->description = $description;

        $this->createdAt = Carbon::now();
        $this->updatedAt = $this->createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }
}
