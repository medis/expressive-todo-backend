<?php declare(strict_types = 1);

namespace MedisDemoApp\Service\Item;

use Doctrine\Common\Persistence\ObjectRepository;
use MedisDemoApp\Entity\Item;
use Ramsey\Uuid\UuidInterface;

class DoctrineFindItemByUuid implements FindItemByUuidInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UuidInterface $id
     * @return Item
     * @throws Exception\BookNotFound
     */
    public function __invoke(UuidInterface $id): Item
    {
        /** @var Item|null $item */
        $item = $this->repository->find((string)$id);
        if (null === $item) {
            throw Exception\ItemNotFound::fromUuid($id);
        }
        return $item;
    }
}