<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\NotificationRepository;
use App\State\NotificationProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiResource(types: ['https://schema.org/Notification'])]
class Notification
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public \DateTime $sent_at;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $type;

    /**
     * Account
     */
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'notifications')]
    private Account $account;

    public function __construct()
    {
        $this->sent_at = new \DateTime();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
