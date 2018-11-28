<?php declare(strict_types=1);

namespace MedisDemoApp\Handler;

use Doctrine\ORM\EntityManagerInterface;
use MedisDemoApp\Entity\Item;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

final class CreateItemHandler implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface
    {
        try {
            $data = $request->getParsedBody();
            $inputFilter = Item::getInputFilter();
            $inputFilter->setData($data);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                $item = new Item($data);

                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return new JsonResponse(['info' => sprintf('Item with name %s already exists', $data['name'])], 422);
        }

        return new JsonResponse([
            'info' => sprintf('You have created %s', $item->getName()),
        ]);
    }
}