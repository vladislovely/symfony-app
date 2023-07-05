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
class BookAreOverHandler
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly NotifierInterface $notifier
    ) {
    }
    public function __invoke(BookHeld $message): void
    {
        $bookId = $message->getBookId();

        $book = $this->bookRepository->findOneBy(['id' => $bookId]);

        if ($book instanceof Book) {
            $accounts = $book->getAccounts();

            var_dump($accounts);
        }
        dd();
    }

    private function send(array $toAddresses): void
    {
        $recipients = [];

        $notification = (new Notification('Notification, Achtung!', ['email']))
            ->content('I’m sorry to say this, but your book is no longer available, expect a new arrival')
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