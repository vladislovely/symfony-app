<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\BookCopyRepository;
use App\State\BookCopyProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('int')]
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
     * Checkout collection
     */
    #[ORM\OneToMany(mappedBy: 'bookCopy', targetEntity: Checkout::class)]
    private ArrayCollection $checkouts;

    /**
     * Hold collection
     */
    #[ORM\OneToMany(mappedBy: 'bookCopy', targetEntity: Hold::class)]
    private ArrayCollection $holds;

    public function __construct()
    {
        $this->checkouts = new ArrayCollection();
        $this->holds = new ArrayCollection();
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
    public function getHolds(): Collection
    {
        return $this->holds;
    }

    /**
     * @return Collection
     */
    public function getCheckouts(): Collection
    {
        return $this->checkouts;
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
