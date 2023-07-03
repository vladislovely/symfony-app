<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\BookRepository;
use App\State\BookProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(provider: BookProvider::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    /**
     * Book title
     */
    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $title;

    /**
     * Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'books')]
    private Category $category;

    /**
     * Authors collection
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private ArrayCollection $authors;

    /**
     * Books copy collection
     */
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookCopy::class)]
    private ArrayCollection $booksCopy;

    /**
     * Account collection
     */
    #[ORM\ManyToMany(targetEntity: Account::class, inversedBy: 'books')]
    private ArrayCollection $accounts;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->booksCopy = new ArrayCollection();
        $this->accounts = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param Author $author
     * @return $this
     */
    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    /**
     * @param Author $author
     * @return $this
     */
    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }
        return $this;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->addBook($this);
        }

        return $this;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            $account->removeBook($this);
        }
        return $this;
    }
}
