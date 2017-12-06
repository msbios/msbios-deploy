<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Controller\IndexController;
use MSBios\ModuleInterface;
use Zend\Config\Config;
use Zend\EventManager\EventInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;
use Zend\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package MSBios\Deploy
 */
class Module implements ModuleInterface, BootstrapListenerInterface, AutoloaderProviderInterface
{
    /** @const VERSION */
    const VERSION = '1.0.13';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $e->getTarget()->getServicemanager();

        /** @var Config $config */
        $config = $serviceLocator->get(self::class);

        /** @var TreeRouteStack $router */
        $router = $serviceLocator->get('Router');

        if ($config->get('enabled') && $router instanceof TreeRouteStack) {
            $serviceLocator->get('Router')->addRoutes([
                self::class => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => $config->get('url'),
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'dispatch'
                        ]
                    ],
                    'child_routes' => [
                        [
                            'type' => Method::class,
                            'options' => [
                                'verb' => 'post'
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }
}
