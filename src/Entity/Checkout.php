<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CheckoutRepository;
use App\State\CheckoutProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CheckoutRepository::class)]
#[ApiResource(provider: CheckoutProvider::class)]
class Checkout
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public \DateTime $start_time;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\NotBlank]
    public \DateTime $end_time;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('boolean')]
    public bool $is_returned;

    #[ORM\ManyToOne(targetEntity: BookCopy::class, inversedBy: 'checkouts')]
    private BookCopy $bookCopy;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'checkouts')]
    private Account $account;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
