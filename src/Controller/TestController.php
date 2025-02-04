<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(LoggerInterface $logger): JsonResponse
    {
        $logger->info('I just got the logger');
        $logger->error('An error occurred');

        // log messages can also contain placeholders, which are variable names
        // wrapped in braces whose values are passed as the second argument
        $logger->debug('User {userId} has logged in', [
            'userId' => 123
        ]);

        $logger->critical('I left the oven on!', [
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ]);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}
