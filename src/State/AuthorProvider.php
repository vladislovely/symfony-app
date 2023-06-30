<?php
namespace App\State;

use App\Entity\Author;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\Operation;
final class AuthorProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {}
}
