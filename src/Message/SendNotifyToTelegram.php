<?php

namespace App\Message;

class SendNotifyToTelegram
{
    public function __construct(
        private readonly string $message,
        private readonly string $chatId,
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }
}