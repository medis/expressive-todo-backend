<?php namespace App\Entity\Exception;

namespace MedisDemoApp\App\Entity\Exception;

use MedisDemoApp\App\Entity\Item;

class ItemAlreadyComplete extends \DomainException
{
    public static function fromItem(Item $item) : self
    {
        return new self(sprintf('%s is already complete', $item->getName()));
    }
}