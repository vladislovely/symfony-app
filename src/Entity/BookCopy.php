<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\BookCopyRepository;
use App\Resolver\CreateBookCopyMutationResolver;
use App\Resolver\CreateBookMutationResolver;
use App\Resolver\ReturnBookMutationResolver;
use App\State\BookCopyProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookCopyRepository::class)]
#[ApiResource(
    types: ['https://schema.org/BookCopy'],
    graphQlOperations: [
        new Query(),
        new QueryCollection(paginationType: 'page'),
        new Mutation(
            resolver: CreateBookCopyMutationResolver::class,
            args: [
                'book_uid' => [
                    'type' => 'String!',
                    'description' => 'Original book uid'
                ],
                'publisher_name' => [
                    'type' => 'String!',
                    'description' => 'Publisher name'
                ],
                'year_published' => [
                    'type' => 'Int!',
                    'description' => 'Year of publish the book'
                ],
                'number_of_copies' => [
                    'type' => 'Int!',
                    'description' => 'Number of copies current publisher'
                ]
            ],
            name: 'create'
        ),
        new Mutation(
            resolver: ReturnBookMutationResolver::class,
            args: [
                'book_copy_uid' => [
                    'type' => 'String!',
                    'description' => 'Book copy uid'
                ],
                'account_uid' => [
                    'type' => 'String!',
                    'description' => 'User account uid'
                ],
            ],
            name: 'return'
        ),
    ]
)]
class BookCopy
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
     * Published year
     */
    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('int')]
    public int $year_published;

    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('int')]
    public int $count;
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
    private Collection $checkouts;

    /**
     * Hold collection
     */
    #[ORM\OneToMany(mappedBy: 'bookCopy', targetEntity: Hold::class)]
    private Collection $holds;

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

    public function reserve(): self
    {
        --$this->count;

        return $this;
    }

    public function return(): self
    {
        ++$this->count;

        return $this;
    }
}
