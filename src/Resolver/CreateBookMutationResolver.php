<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class CreateBookMutationResolver implements MutationResolverInterface
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @param Book|null $item
     * @param array $context
     * @return object|null
     */
    public function __invoke($item, array $context): ?Book
    {
        $data = $context['args']['input'];

        $findBook = $this->em->getRepository(Book::class)->findOneBy(['title' => $data['title']]);

        if (!empty($findBook)) {
            throw new RuntimeException('Book with this title already exist');
        }

        $category = $this->em->getRepository(Category::class)->findOneBy(['title' => $data['category_name']]);
        $authors = [];

        if ($category === null) {
            $newCategory = new Category();
            $newCategory->title = $data['category_name'];

            $this->em->persist($newCategory);
            $this->em->flush();

            $category = $newCategory;
        }

        if (!empty($data['author_names']) && count($data['author_names']) > 0) {
            $authors = $this->findAuthorOrCreate($data['author_names']);
        }

        if ($category instanceof Category) {
            $book = new Book();
            $book->setCategory($category);
            $book->addAuthors($authors);

            $book->title = $data['title'];

            $this->em->persist($book);
            $this->em->flush();

            return $book;
        }

        return null;
    }

    private function findAuthorOrCreate(array $data): array
    {
        $authors = [];

        $authorRepository = $this->em->getRepository(Author::class);

        if ($authorRepository instanceof AuthorRepository) {
            $authorsQuery = $authorRepository->findByName(['name' => $data]);

            if (empty($authorsQuery)) {
                foreach ($data as $name) {
                    $newAuthor = new Author();
                    $newAuthor->name = $name;

                    $this->em->persist($newAuthor);
                    $authors[] = $newAuthor;
                }

                $this->em->flush();
            }

            if (!empty($authorsQuery)) {
                return $authorsQuery;
            }
        }

        return $authors;
    }
}