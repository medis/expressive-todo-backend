<?php declare(strict_types = 1);

namespace MedisDemoApp\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

class CreateItemHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return CreateItemHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CreateItemHandler
    {
        return new CreateItemHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}