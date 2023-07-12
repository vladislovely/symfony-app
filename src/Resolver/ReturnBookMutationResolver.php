<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Account;
use App\Entity\BookCopy;
use App\Entity\Hold;
use App\Message\BookIsAvailable;
use App\Repository\AccountRepository;
use App\Repository\BookRepository;
use App\Repository\HoldRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class ReturnBookMutationResolver implements MutationResolverInterface
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface    $bus,
    )
    {}

    /**
     * @param Hold|null $item
     * @param array $context
     * @return object|null
     */
    public function __invoke($item, array $context): ?object
    {
        $data = $context['args']['input'];

        $result = $this->validate($data);

        $originalBookId = null;

        if ($result['book_copy'] instanceof BookCopy && $result['account'] instanceof Account) {
            $findRecord = $this->em->getRepository(Hold::class)->findOneBy(['bookCopy' => $result['book_copy']->getId(), 'account' => $result['account']->getId()]);

            if ($findRecord instanceof Hold) {
                $result['book_copy']->return();

                $this->em->remove($findRecord);
                $this->em->flush();

                $originalBookId = $result['book_copy']->getBook()?->getId();
            }
        }

        if ($originalBookId !== null) {
            $this->bus->dispatch(new BookIsAvailable($originalBookId, 'telegram'));
        }

        return new JsonResponse('Book successful returned!');
    }

    private function validate(array $data): array
    {
        $result = [];

        $isValidBookUid = Uuid::isValid($data['book_copy_uid']);
        $isValidAccountUid = Uuid::isValid($data['account_uid']);

        if ($isValidBookUid === false) {
            throw new RuntimeException('book_copy_uid is not valid or empty, check it and try again');
        }

        if ($isValidAccountUid === false) {
            throw new RuntimeException('account_uid is not valid or empty, check it and try again');
        }

        $checkIsBookExists = $this->em->getRepository(BookCopy::class)->findOneBy(['id' => $data['book_copy_uid']]);
        $checkIsAccountExists = $this->em->getRepository(Account::class)->findOneBy(['id' => $data['account_uid']]);
        $holdRepository = $this->em->getRepository(Hold::class);

        if ($holdRepository instanceof HoldRepository) {
            $isUserAlreadyHoldThisBook = $holdRepository->findHoldByBookCopyIdAndAccountId($data['book_copy_uid'], $data['account_uid']);

            if ($isUserAlreadyHoldThisBook === null) {
                throw new RuntimeException('This user is not reserving this book');
            }
        }

        if ($checkIsBookExists === null) {
            throw new RuntimeException('Book Copy with provided book_copy_uid does not exists');
        }

        if ($checkIsAccountExists === null) {
            throw new RuntimeException('Account with provided account_uid does not exists');
        }

        $result['book_copy'] = $checkIsBookExists;
        $result['account'] = $checkIsAccountExists;

        return $result;
    }
}