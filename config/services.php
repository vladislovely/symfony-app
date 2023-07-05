<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Identifier\UuidUriVariableTransformer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('App\\', '../src/')
        ->exclude('../src/{DependencyInjection,Entity,Kernel.php}');

    $services->set(UuidUriVariableTransformer::class)
        ->tag('api_platform.uri_variables.transformer');
};
