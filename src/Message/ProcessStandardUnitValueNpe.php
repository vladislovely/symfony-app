<?php

namespace App\Message;

final class ProcessStandardUnitValueNpe
{
     public function __construct(
         private readonly array $npes,
         private readonly int $uveId,
     ) {
     }

    public function getNpes(): array
    {
        return $this->npes;
    }

    public function getUveId(): int
    {
        return $this->uveId;
    }
}
