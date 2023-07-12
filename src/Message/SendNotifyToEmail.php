<?php

namespace App\Message;

class SendNotifyToEmail
{
    public function __construct(
        private readonly string $subject,
        private readonly string $content,
        private readonly string $importance,
        private readonly array $emails,
    ) {}

    public function getAddresses(): array
    {
        return $this->emails;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImportance(): string
    {
        return $this->importance;
    }
}