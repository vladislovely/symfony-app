<?php

namespace App\Message;

class BookHeld
{
    public function __construct(
        private readonly string $bookId,
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }
}