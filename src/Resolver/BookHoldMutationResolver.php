<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Account;
use App\Entity\BookCopy;
use App\Entity\Hold;
use App\Message\BookHeld;
use App\Message\BooksAreOver;
use App\Repository\AccountRepository;
use App\Repository\BookRepository;
use App\Repository\HoldRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class BookHoldMutationResolver implements MutationResolverInterface
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

        $bookId = null;

        $hold = new Hold();
        $hold->setAccount($result['account']);
        $hold->setBookCopy($result['book_copy']);
        $hold->start_time = $result['start_date'];

        if (array_key_exists('end_date', $result)) {
            $hold->end_time = $result['end_date'];
        } else {
            $hold->end_time = null;
        }

        if ($result['book_copy'] instanceof BookCopy) {
            $result['book_copy']->reserve();

            $bookId = $result['book_copy']->getBook()?->getId();
        }

        $this->em->persist($hold);
        $this->em->flush();

        if ($bookId !== null) {
            $this->bus->dispatch(new BookHeld($bookId));
        }

        if ($result['book_copy'] instanceof BookCopy && $result['book_copy']->count === 0 && $bookId !== null) {
            $this->bus->dispatch(new BooksAreOver($bookId));
        }

        return $hold;
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

            if ($isUserAlreadyHoldThisBook instanceof Hold) {
                throw new RuntimeException('Provided account_id already reserved this book_copy');
            }
        }

        if ($checkIsBookExists === null) {
            throw new RuntimeException('Book Copy with provided book_copy_uid does not exists');
        }

        if ($checkIsBookExists->count === 0) {
            throw new RuntimeException('Book Copy with provided book_copy_uid is not available');
        }

        if ($checkIsAccountExists === null) {
            throw new RuntimeException('Account with provided account_uid does not exists');
        }

        $isStartTimeDate = $this->validateDate($data['start_date']);

        if ($isStartTimeDate === false) {
            throw new RuntimeException('start_date is not valid or empty, check it and try again');
        }

        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $startDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['start_date']);

        if ($now > $startDate) {
            throw new RuntimeException('start_date can not be less then now');
        }

        $result['book_copy'] = $checkIsBookExists;
        $result['account'] = $checkIsAccountExists;
        $result['start_date'] = $startDate;

        if (!empty($data['end_date'])) {
            $isEndDateDate = $this->validateDate($data['end_date']);

            if ($isEndDateDate === false) {
                throw new RuntimeException('end_date is not valid or empty, check it and try again');
            }

            $endDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['end_date']);

            if ($now > $endDate) {
                throw new RuntimeException('end_date can not be less then now');
            }

            if ($startDate > $endDate) {
                throw new RuntimeException('start_date can not be more then end_date');
            }

            $result['end_date'] = $endDate;
        }

        return $result;
    }

    private function validateDate(string $date): bool
    {
        $d = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);

        return $d && $d->format('Y-m-d H:i:s') === $date;
    }
}