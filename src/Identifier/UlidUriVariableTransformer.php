<?php declare(strict_types=1);

namespace App\Identifier;

use ApiPlatform\Api\UriVariableTransformerInterface;
use ApiPlatform\Exception\InvalidUriVariableException;
use Symfony\Component\Uid\Ulid;

/**
 * Transforms an ULID string to an instance of Symfony\Component\Uid\Ulid.
 */
final class UlidUriVariableTransformer implements UriVariableTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value, array $types, array $context = []): Ulid
    {
        try {
            return Ulid::fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidUriVariableException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation(mixed $value, array $types, array $context = []): bool
    {
        return \is_string($value) && is_a($types[0], Ulid::class, true);
    }
}