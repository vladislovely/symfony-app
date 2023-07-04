<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\HoldRepository;
use App\Resolver\BookHoldMutationResolver;
use App\State\HoldProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HoldRepository::class)]
#[ApiResource(
    types: ['https://schema.org/Hold'],
    graphQlOperations: [
        new Query(),
        new QueryCollection(paginationType: 'page'),
        new Mutation(
            resolver: BookHoldMutationResolver::class,
            args: [
                'book_copy_uid' => [
                    'type' => 'String!',
                    'description' => 'Book copy uid'
                ],
                'account_uid' => [
                    'type' => 'String!',
                    'description' => 'Account uid'
                ],
                'start_date' => [
                    'type' => 'String!',
                    'description' => 'Date start hold book, format: Y-m-d H:i:s',
                ],
                'end_date' => [
                    'type' => 'String!',
                    'description' => 'End date hold book, format: Y-m-d H:i:s',
                ]
            ],

            name: 'create'
        )
    ]
)]
class Hold
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public \DateTimeImmutable $start_time;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $end_time;

    #[ORM\ManyToOne(targetEntity: BookCopy::class, inversedBy: 'holds')]
    private BookCopy $bookCopy;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'holds')]
    private Account $account;
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
    public function getBookCopy(): ?BookCopy
    {
        return $this->bookCopy;
    }

    /**
     * @param BookCopy|null $bookCopy
     * @return $this
     */
    public function setBookCopy(?BookCopy $bookCopy): self
    {
        $this->bookCopy = $bookCopy;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     * @return $this
     */
    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
