<?php

use App\Controller\OfferController;
use App\Controller\ProductController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->add('products_collection', '/api/products')
           ->controller([ProductController::class, 'index'])
           ->methods(['GET']);

    $routes->add('get_product', '/api/products/{id}')
           ->controller([ProductController::class, 'getProduct'])
           ->methods(['GET']);

    $routes->add('create_product', '/api/products')
           ->controller([ProductController::class, 'createProduct'])
           ->methods(['POST']);

    $routes->add('update_product', '/api/products/{id}')
           ->controller([ProductController::class, 'updateProduct'])
           ->methods(['PUT']);

    $routes->add('edit_product', '/api/products/{id}')
           ->controller([ProductController::class, 'editProduct'])
           ->methods(['PATCH']);

    $routes->add('delete_product', '/api/products/{id}')
           ->controller([ProductController::class, 'deleteProduct'])
           ->methods(['DELETE']);


    $routes->add('offers_collection', '/api/offers')
           ->controller([OfferController::class, 'index'])
           ->methods(['GET']);

    $routes->add('get_offer', '/api/offers/{id}')
           ->controller([OfferController::class, 'getOffer'])
           ->methods(['GET']);

    $routes->add('create_offer', '/api/offers')
           ->controller([OfferController::class, 'createOffer'])
           ->methods(['POST']);

    $routes->add('update_offer', '/api/offers/{id}')
           ->controller([OfferController::class, 'updateOffer'])
           ->methods(['PUT']);

    $routes->add('edit_offer', '/api/offers/{id}')
           ->controller([OfferController::class, 'editOffer'])
           ->methods(['PATCH']);

    $routes->add('delete_offer', '/api/offers/{id}')
           ->controller([OfferController::class, 'deleteOffer'])
           ->methods(['DELETE']);
};
