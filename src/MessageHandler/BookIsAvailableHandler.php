<?php

namespace App\MessageHandler;

use App\Message\BookIsAvailable;
use App\Message\SendNotifyToEmail;
use App\Message\SendNotifyToTelegram;
use App\Repository\AccountRepository;
use App\Repository\BookRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Notification\Notification;

#[AsMessageHandler]
class BookIsAvailableHandler
{
    private const CHAT_ID = '712270239';

    public function __construct(
        private readonly BookRepository      $bookRepository,
        private readonly AccountRepository   $accountRepository,
        private readonly LoggerInterface     $logger,
        private readonly MessageBusInterface $bus
    )
    {
    }

    public function __invoke(BookIsAvailable $message): void
    {
        $bookId = $message->getBookId();

        $this->logger->info('Someone returned the book, need send notification for people who waiting this book');
        $this->logger->info('Received bookId = {bookId}', ['bookId' => $bookId]);
        $this->logger->info('Trying to find accounts which waiting this book...');

        switch ($message->getChannel()) {
            case 'sms':
                break;
            case 'telegram':
                $this->createTelegramJob($bookId);
                break;
            default:
                $this->createEmailJob($bookId);
                break;
        }
    }


    private function createEmailJob(string $bookId): void
    {
        $this->logger->info('Chose transporter email');
        $this->logger->info('Trying to find accounts which waiting this book...');

        $accountsIds = $this->bookRepository->getAccounts($bookId);

        if (!empty($accountsIds)) {
            $this->logger->info('Found accounts = {account_ids}, trying get emails', ['account_ids' => $accountsIds]);

            $emails = $this->accountRepository->getEmailsByIds($accountsIds);

            if (!empty($emails)) {
                $result = array_column($emails, 'email');

                $this->logger->info('Found emails = {emails}, trying to send notifications', ['emails' => $result]);

                $this->bus->dispatch(new SendNotifyToEmail('Notification, Attention!', 'Book from your waitlist, now is available, it is time for order right now!', Notification::IMPORTANCE_MEDIUM, $result));
            }
        }
    }

    private function createTelegramJob(string $bookId): void
    {
        $this->logger->info('Chose transporter telegram');

        $this->bus->dispatch(new SendNotifyToTelegram('Book from your waitlist, now is available, it is time for order right now!', self::CHAT_ID));
    }
}