<?php

namespace App\MessageHandler;

use App\Dto\UveDataDto;
use App\Message\CreateStandardUnitValue;
use App\Message\LoadExtendedDataByUveId;
use App\Message\UveListProcessing;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class UveListProcessingHandler
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function __invoke(UveListProcessing $message): void
    {
        foreach ($message->getList() as $value) {
            $uveDto = new UveDataDto($value);

            $this->bus->dispatch(new CreateStandardUnitValue(
                uveDataDto: $uveDto,
                url: $message->getUrl() . '/' . $uveDto->uveId
            ));
        }
    }
}
