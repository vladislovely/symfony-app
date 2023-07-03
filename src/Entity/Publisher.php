<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\PublisherRepository;
use App\State\PublisherProvider;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: PublisherRepository::class)]
#[ApiResource(types: ['https://schema.org/Publisher'])]
class Publisher
{
    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    /**
     * Publisher title
     */
    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $title;

    #[ORM\OneToMany(mappedBy: 'publisher', targetEntity: BookCopy::class)]
    private Collection $booksCopy;

    public function __construct()
    {
        $this->booksCopy = new ArrayCollection();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getBooksCopy(): Collection
    {
        return $this->booksCopy;
    }
}
