<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

final class AccountProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {}
}
