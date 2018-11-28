<?php declare(strict_types=1);

namespace MedisDemoApp;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'routes'       => $this->getRoutes()
        ];
    }

    public function getDependencies() : array
    {
        return [
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    \Zend\Expressive\Container\ApplicationConfigInjectionDelegator::class,
                ],
            ]
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
            ]
        ];
    }
}