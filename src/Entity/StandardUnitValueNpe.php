<?php

namespace App\Entity;

use App\Repository\StandardUnitValueNpeRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: StandardUnitValueNpeRepository::class)]
class StandardUnitValueNpe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $standardUnitValueId;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $standardStatePrimaryId;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $orderId;

    #[ORM\Column('created_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $createdAt;

    #[ORM\Column('updated_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column('deleted_at', type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private ?DateTimeImmutable $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStandardUnitValueId(): int
    {
        return $this->standardUnitValueId;
    }

    public function setStandardUnitValueId(int $standardUnitValueId): void
    {
        $this->standardUnitValueId = $standardUnitValueId;
    }

    public function getStandardStatePrimaryId(): int
    {
        return $this->standardStatePrimaryId;
    }

    public function setStandardStatePrimaryId(int $standardStatePrimaryId): void
    {
        $this->standardStatePrimaryId = $standardStatePrimaryId;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getDeletedAt(): DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
