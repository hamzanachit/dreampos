<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ProductController;
use Application\Service\ProductService; // Ensure this is imported correctly

class ProductControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Inject dependencies and create ProductController instance
        $productService = $container->get(ProductService::class);
        return new ProductController($productService);
    }
}