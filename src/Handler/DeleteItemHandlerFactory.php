<?php declare(strict_types = 1);

namespace MedisDemoApp\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use MedisDemoApp\Service\Item\FindItemByUuidInterface;

class DeleteItemHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return DeleteItemHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DeleteItemHandler
    {
        return new DeleteItemHandler(
            $container->get(FindItemByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}