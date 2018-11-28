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

final class UpdateItemHandler implements MiddlewareInterface
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

        $data = $request->getParsedBody();
        $inputFilter = Item::getInputFilter();
        $inputFilter->setData($data);
        if (!$inputFilter->isValid()) {
            return new JsonResponse(['info' => 'Invalid data', 422]);
        }

        try {
            $this->entityManager->transactional(function () use ($item, $inputFilter) {
                $data = $inputFilter->getValues();
                // @todo Figure a better way.
                // Throws unique field error if name exists in data.
                unset($data['name']);
                $item->data($data);
            });
        } catch (\Exception $e) {
            print_r(['error', $e->getMessage()]);die;
        }

        return new JsonResponse([
            'info' => sprintf('You have updated %s', $item->getName()),
        ]);
    }
}