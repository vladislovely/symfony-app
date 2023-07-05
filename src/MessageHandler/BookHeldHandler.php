<?php

namespace App\MessageHandler;

use App\Entity\Book;
use App\Message\BookHeld;
use App\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AsMessageHandler]
class BookHeldHandler
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly NotifierInterface $notifier
    ) {
    }
    public function __invoke(BookHeld $message): void
    {
        $bookId = $message->getBookId();

        var_dump('ewq');
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);

        var_dump($book?->title);
        if ($book instanceof Book) {
            $accounts = $book->getAccounts()->toArray();

            var_dump($accounts);
        }
        die();
    }


    private function send(array $toAddresses): void
    {
        $recipients = [];

        $notification = (new Notification('Notification, Achtung!', ['email']))
            ->content('Someone already took the book, what are you waiting for, hurry up, or it won’t stay!')
            ->importance(Notification::IMPORTANCE_MEDIUM);

        foreach ($toAddresses as $address) {
            $recipient = new Recipient(
                $address
            );

            $recipients[] = $recipient;
        }

        $this->notifier->send($notification, ...$recipients);
    }
}