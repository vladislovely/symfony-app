<?php

namespace App\MessageHandler;

use App\Message\SendNotifyToEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AsMessageHandler]
class SendNotifyToEmailHandler
{
    public function __construct(
        private readonly NotifierInterface $notifier,
    )
    {
    }

    public function __invoke(SendNotifyToEmail $message): void
    {
        $recipients = [];

        $notification = (new Notification($message->getSubject(), ['email']))
            ->content($message->getContent())
            ->importance($message->getImportance());

        $notification->emoji($notification->getEmoji());

        foreach ($message->getAddresses() as $address) {
            $recipient = new Recipient(
                $address
            );

            $recipients[] = $recipient;
        }

        $this->notifier->send($notification, ...$recipients);
    }
}