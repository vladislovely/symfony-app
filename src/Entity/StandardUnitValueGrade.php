<?php

namespace App\Entity;

use App\Repository\StandardUnitValueGradeRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: StandardUnitValueGradeRepository::class)]
class StandardUnitValueGrade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $standardUnitValueId;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $gradeStandardId;

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

    #[ORM\Column('created_by', type: Types::INTEGER, nullable: false)]
    private int $createdBy = 1;

    #[ORM\Column('updated_by', type: Types::INTEGER, nullable: false)]
    private int $updatedBy = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getGradeStandardId(): string
    {
        return $this->gradeStandardId;
    }

    public function setGradeStandardId(int $gradeStandardId): void
    {
        $this->gradeStandardId = $gradeStandardId;
    }

    public function getStandardUnitValueId(): string
    {
        return $this->standardUnitValueId;
    }

    public function setStandardUnitValueId(int $standardUnitValueId): void
    {
        $this->standardUnitValueId = $standardUnitValueId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
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

    public function getUpdatedBy(): int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
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
