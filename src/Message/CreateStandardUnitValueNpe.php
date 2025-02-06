<?php

namespace App\Message;

final class CreateStandardUnitValueNpe
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private readonly int $standardStatePrimaryId,
         private readonly int $uveId,
         private readonly int $order,
     ) {
     }

    public function getStandardStatePrimaryId(): int
    {
        return $this->standardStatePrimaryId;
    }

    public function getUveId(): int
    {
        return $this->uveId;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
