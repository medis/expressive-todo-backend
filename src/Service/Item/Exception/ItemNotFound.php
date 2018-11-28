<?php declare(strict_types = 1);

namespace MedisDemoApp\Service\Item\Exception;

use Ramsey\Uuid\UuidInterface;

class ItemNotFound extends \RuntimeException
{
    public static function fromUuid(UuidInterface $uuid) : self
    {
        return new self(sprintf('Item with UUID %s is not found', (string)$uuid));
    }
}