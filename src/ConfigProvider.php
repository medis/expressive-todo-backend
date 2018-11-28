<?php declare(strict_types=1);

namespace MedisDemoApp;

use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\Mysqli\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'routes'       => $this->getRoutes(),
            'doctrine'     => $this->getDoctrine()
        ];
    }

    public function getDependencies() : array
    {
        return [
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    \Zend\Expressive\Container\ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'  => [
                EntityManagerInterface::class => EntityManagerFactory::class,
                Middleware\ErrorCatchingMiddleware::class => InvokableFactory::class,
                Service\Item\FindItemByUuidInterface::class => Service\Item\DoctrineFindItemByUuidFactory::class,
                Handler\CreateItemHandler::class => Handler\CreateItemHandlerFactory::class,
                Handler\UpdateItemHandler::class => Handler\UpdateItemHandlerFactory::class,
            ]
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'connection' => [
                'orm_default' => [
                    'driver_class' => Driver::class
                ],
            ],
            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'MedisDemoApp\Entity' => 'app_entity',
                    ],
                ],
                'app_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [
                        __DIR__ . '/Entity',
                    ],
                ],
            ],
        ];
    }

    /**
     * Return available routes.
     * @return array
     */
    public function getRoutes() : array
    {
        return [
            'demo' => [
                'path'            => '/demo',
                'middleware'      => Handler\DemoHandler::class,
                'allowed_methods' => ['GET'],
            ],
            'item.create' => [
                'path'            => '/item',
                'middleware'      => Handler\CreateItemHandler::class,
                'allowed_methods' => ['POST']
            ],
            'item.update' => [
                'path'            => '/item/{id:\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b}/update',
                'middleware'      => Handler\UpdateItemHandler::class,
                'allowed_methods' => ['POST']
            ]
        ];
    }
}