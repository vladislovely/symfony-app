<?php

namespace App\Message;

class BooksAreOver
{
    public function __construct(
        private readonly string $bookId,
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }
}