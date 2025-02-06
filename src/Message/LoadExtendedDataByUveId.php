<?php

namespace App\Message;

final class LoadExtendedDataByUveId
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private readonly string $uri,
         private readonly int $uveId,
     ) {
     }

     public function getUri(): string
     {
         return $this->uri;
     }

     public function getUveId(): int
     {
         return $this->uveId;
     }
}
