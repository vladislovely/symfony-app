<?php

namespace App\MessageHandler;

use App\Message\BookIsAvailable;
use App\Repository\AccountRepository;
use App\Repository\BookRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AsMessageHandler]
class BookIsAvailableHandler
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly AccountRepository $accountRepository,
        private readonly NotifierInterface $notifier,
        private readonly LoggerInterface $logger,
    ) {}
    public function __invoke(BookIsAvailable $message): void
    {
        $bookId = $message->getBookId();

        $this->logger->info('Someone returned the book, need send notification for people who waiting this book');
        $this->logger->info('Received bookId = {bookId}', ['bookId' => $bookId]);
        $this->logger->info('Trying to find accounts which waiting this book...');

        $accountsIds = $this->bookRepository->getAccounts($bookId);

        if (!empty($accountsIds)) {
            $this->logger->info('Found accounts = {account_ids}, trying get emails', ['account_ids' => $accountsIds]);

            $emails = $this->accountRepository->getEmailsByIds($accountsIds);

            if (!empty($emails)) {
                $result = array_column($emails, 'email');

                $this->logger->info('Found emails = {emails}, trying to send notifications', ['emails' => $result]);

                $this->send($result);
            }
        }
    }


    private function send(array $toAddresses): void
    {
        $recipients = [];

        $notification = (new Notification('Notification, Attention!', ['email']))
            ->content('Book from your waitlist, now is available, it is time for order right now!')
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