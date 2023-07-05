<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

use App\Repository\AccountRepository;
use App\State\AccountProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ApiResource(types: ['https://schema.org/Account'])]
class Account
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $first_name;
    #[ORM\Column(type: Types::STRING, length: 100, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $surname;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    public string $email;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('boolean')]
    public bool $status;

    /**
     * Notification collection
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Notification::class)]
    private Collection $notifications;

    /**
     * Books collection
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'accounts')]
    private Collection $books;

    /**
     * Checkout collection
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Checkout::class)]
    private Collection $checkouts;

    /**
     * Holds collection
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Hold::class)]
    private Collection $holds;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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
     * @return Collection
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @param Book $book
     * @return $this
     */
    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAccount($this);
        }

        return $this;
    }

    /**
     * @param Book $book
     * @return $this
     */
    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeAccount($this);
        }
        return $this;
    }
}
