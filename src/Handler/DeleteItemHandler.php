<?php declare(strict_types=1);

namespace MedisDemoApp\Handler;

use Doctrine\ORM\EntityManagerInterface;
use MedisDemoApp\Entity\Item;
use MedisDemoApp\Service\Item\Exception\ItemNotFound;
use MedisDemoApp\Service\Item\FindItemByUuidInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

final class DeleteItemHandler implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FindItemByUuidInterface
     */
    private $findItemByUuid;

    public function __construct(FindItemByUuidInterface $findItemByUuid, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->findItemByUuid = $findItemByUuid;
    }

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface
    {
        try {
            $item = $this->findItemByUuid->__invoke(Uuid::fromString($request->getAttribute('id')));
        } catch (ItemNotFound $itemNotFound) {
            return new JsonResponse(['info' => $itemNotFound->getMessage()], 404);
        }

        try {
            $this->entityManager->transactional(function () use ($item) {
                $this->entityManager->remove($item);
            });
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 422);
        }

        return new JsonResponse([
            'info' => sprintf('You have deleted %s', $item->getName()),
        ]);
    }
}