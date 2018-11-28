<?php declare(strict_types = 1);

namespace MedisDemoApp\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use MedisDemoApp\Service\Item\FindItemByUuidInterface;

class UpdateItemHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return CreateItemHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : UpdateItemHandler
    {
        return new UpdateItemHandler(
            $container->get(FindItemByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}