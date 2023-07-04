<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Book;
use App\Entity\BookCopy;
use App\Entity\Publisher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Validator\Constraints\Uuid as UuidConstraint;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\Collection;


class CreateBookCopyMutationResolver implements MutationResolverInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param BookCopy|null $item
     * @param array $context
     * @return object|null
     */
    public function __invoke($item, array $context): ?BookCopy
    {
        $data = $context['args']['input'];

        if (empty($data['book_uid'])) {
            throw new RuntimeException('book_uid can not be empty');
        }

        if (empty($data['publisher_name'])) {
            throw new RuntimeException('publisher_name can not be empty');
        }

        $validator = Validation::createValidator();

        $uuidContraint = new UuidConstraint();
        $uuidContraint->message = 'book_uid does not UUID type';

        $errors = $validator->validate(
            $data['book_uid'],
            $uuidContraint
        );

        if ($errors->count() !== 0) {
            throw new RuntimeException($errors->get(0)->getMessage());
        }

        $findBook = $this->em->getRepository(Book::class)->findOneBy(['id' => $data['book_uid']]);

        if ($findBook === null) {
            throw new RuntimeException('Book with this uid does not exist');
        }

        $publisher = $this->em->getRepository(Publisher::class)->findOneBy(['title' => $data['publisher_name']]);

        if ($publisher === null) {
            $newPublisher = new Publisher();
            $newPublisher->title = $data['publisher_name'];

            $this->em->persist($newPublisher);
            $this->em->flush();

            $publisher = $newPublisher;
        }

        if ($publisher instanceof Publisher && $findBook instanceof Book && $data['number_of_copies'] !== 0) {
            $bookCopy = new BookCopy();
            $bookCopy->setBook($findBook);
            $bookCopy->setPublisher($publisher);
            $bookCopy->year_published = $data['year_published'];
            $bookCopy->count = $data['number_of_copies'];

            $this->em->persist($bookCopy);
            $this->em->flush();

            return $bookCopy;
        }

        return null;
    }
}