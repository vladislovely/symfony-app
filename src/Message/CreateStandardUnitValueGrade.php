<?php

namespace App\Message;

final class CreateStandardUnitValueGrade
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private readonly int $standardUnitValueId,
         private readonly int $gradeStandardId,
         private readonly int $order,
     ) {
     }

    public function getStandardUnitValueId(): int
    {
        return $this->standardUnitValueId;
    }

    public function getGradeStandardId(): int
    {
        return $this->gradeStandardId;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
