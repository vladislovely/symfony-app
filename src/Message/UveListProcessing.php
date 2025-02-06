<?php

namespace App\Message;

final class UveListProcessing
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */
     public function __construct(
         private readonly array $list,
         private readonly string $url,
     ) {
     }

     public function getList(): array
     {
         return $this->list;
     }

    public function getUrl(): string
    {
        return $this->url;
    }
}
