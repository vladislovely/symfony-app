<?php

namespace App\Message;

final class ProcessStandardUnitValueGrade
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private readonly string $rank,
         private readonly int $uveId
     ) {
     }

     public function getRank(): string
     {
         return $this->rank;
     }

     public function getUveId(): int
     {
         return $this->uveId;
     }
}
