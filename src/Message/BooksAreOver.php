<?php

namespace App\Message;

class BooksAreOver
{
    public function __construct(
        private readonly string $bookId,
        private readonly string $channel,
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }
}