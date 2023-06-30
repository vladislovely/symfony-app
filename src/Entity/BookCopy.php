<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\BookCopyRepository;
use App\State\BookCopyProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookCopyRepository::class)]
#[ApiResource(provider: BookCopyProvider::class)]
class BookCopy
{
    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    /**
     * Published year
     */
    #[ORM\Column(type: Types::SMALLINT, length: 4)]
    #[Assert\NotBlank]
    public int $year_published;

    /**
     * Book
     */
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'booksCopy')]
    private Book $book;

    /**
     * Publisher
     */
    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'booksCopy')]
    private Publisher $publisher;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Book|null
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book|null $book
     * @return $this
     */
    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return Publisher|null
     */
    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    /**
     * @param Publisher|null $publisher
     * @return $this
     */
    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }
}
