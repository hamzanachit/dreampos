<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\SalesController;
use Application\Service\SalesService;
use Laminas\View\Renderer\RendererInterface;

class SalesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Retrieve SalesService and RendererInterface from the container
        $salesService = $container->get(SalesService::class);
        $viewRenderer = $container->get(RendererInterface::class); // Get the RendererInterface

        // Pass both dependencies to the SalesController constructor
        return new SalesController($salesService, $viewRenderer);
    }
}