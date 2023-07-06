<?php

namespace App\Message;

class BookIsAvailable
{
    public function __construct(
        private readonly string $bookId,
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }
}