<?php declare(strict_types = 1);

namespace MedisDemoApp\Service\Item;

use MedisDemoApp\Entity\Item;
use Ramsey\Uuid\UuidInterface;

interface FindItemByUuidInterface
{
    /**
     * @param UuidInterface $slug
     * @return Item
     * @throws Exception\ItemNotFound
     */
    public function __invoke(UuidInterface $slug): Item;
}