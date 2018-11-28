<?php declare(strict_types = 1);

namespace MedisDemoApp\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use MedisDemoApp\Entity\Item;

class DoctrineFindItemByUuidFactory
{
    /**
     * @param ContainerInterface $container
     * @return callable
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : callable
    {
        return new DoctrineFindItemByUuid($container->get(EntityManagerInterface::class)->getRepository(Item::class));
    }
}