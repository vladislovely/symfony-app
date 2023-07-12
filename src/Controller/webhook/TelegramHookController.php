<?php

namespace App\Controller\webhook;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelegramHookController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function receiveMessage(
        string                     $name,
        string                     $token,
        Request                    $request,
    ): Response
    {
       $this->logger->debug('Received message', $request->toArray());
    }
}
