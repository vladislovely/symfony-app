<?php

namespace App\Handler;

use App\Entity\Book;

class BookHandler
{
    public function handle(Book $book)
    {
        var_dump($book); die();
    }
}