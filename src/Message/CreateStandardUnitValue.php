<?php

namespace App\Message;

use App\Dto\UveDataDto;

final readonly class CreateStandardUnitValue
{
     public function __construct(
         private UveDataDto $uveDataDto,
         private string $url,
     ) {
     }

     public function getUveDataDto(): UveDataDto
     {
         return $this->uveDataDto;
     }

    public function getUrl(): string
    {
        return $this->url;
    }
}
