<?php

namespace App\Entity;

use App\Repository\StandardUnitValueRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: StandardUnitValueRepository::class)]
class StandardUnitValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::INTEGER, length: 255, nullable: false)]
    private int $uveId;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $number;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: false)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    private ?string $actNo = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, length: 128, nullable: true)]
    private ?DateTimeImmutable $actDate = null;

    #[ORM\Column('interval', type: Types::INTEGER, nullable: true)]
    private ?int $interval = null;

    #[ORM\Column('created_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $createdAt;

    #[ORM\Column('updated_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column('created_by', type: Types::INTEGER, nullable: false)]
    private int $createdBy = 1;

    #[ORM\Column('updated_by', type: Types::INTEGER, nullable: false)]
    private int $updatedBy = 1;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUveId(): int
    {
        return $this->uveId;
    }

    public function setUveId(int $uveId): void
    {
        $this->uveId = $uveId;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getActNo(): ?string
    {
        return $this->actNo;
    }

    public function setActNo(?string $actNo): void
    {
        $this->actNo = $actNo;
    }

    public function getActDate(): ?string
    {
        return $this->actDate;
    }

    public function setActDate(?DateTimeImmutable $actDate): void
    {
        $this->actDate = $actDate;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(?int $interval): void
    {
        $this->interval = $interval;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
