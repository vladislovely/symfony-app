<?php

use App\Controller\webhook\TelegramHookController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->add('receive_message', '/webhook/telegram/{name}/{token}/message')
        ->controller([TelegramHookController::class, 'receiveMessage'])
        ->methods(['POST'])
    ;
};
