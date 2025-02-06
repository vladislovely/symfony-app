<?php

namespace App\Entity;

use App\Repository\StandardStatePrimaryRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: StandardStatePrimaryRepository::class)]
class StandardStatePrimary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, length: 255, nullable: false)]
    private int $npeId;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: false)]
    private string $number;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: false)]
    private string $type;

    #[ORM\Column(type: Types::STRING, length: 500, nullable: false)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 500, nullable: true)]
    private string $institute;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $certificationYear;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $approvalYear;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private string $status;

    #[ORM\Column('created_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $createdAt;

    #[ORM\Column('updated_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column('deleted_at', type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $deletedAt;

    #[ORM\Column('created_by', type: Types::INTEGER, nullable: false)]
    private int $createdBy = 1;

    #[ORM\Column('updated_by', type: Types::INTEGER, nullable: false)]
    private int $updatedBy = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNpeId(): int
    {
        return $this->npeId;
    }

    public function setNpeId(int $npeId): void
    {
        $this->npeId = $npeId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getInstitute(): string
    {
        return $this->institute;
    }

    public function setInstitute(string $institute): void
    {
        $this->institute = $institute;
    }

    public function getCertificationYear(): int
    {
        return $this->certificationYear;
    }

    public function setCertificationYear(int $certificationYear): void
    {
        $this->certificationYear = $certificationYear;
    }

    public function getApprovalYear(): int
    {
        return $this->approvalYear;
    }

    public function setApprovalYear(int $approvalYear): void
    {
        $this->approvalYear = $approvalYear;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
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
