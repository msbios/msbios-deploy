<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Controller\IndexController;
use MSBios\ModuleInterface;
use MSBios\Monolog\LoggerManager;
use Psr\Log\LoggerInterface;
use Zend\Config\Config;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package MSBios\Deploy
 */
class Module implements ModuleInterface, BootstrapListenerInterface
{
    const VERSION = '0.0.1';

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

        /** @var LoggerInterface $logger */
        $logger = $serviceLocator->get(LoggerManager::class)->get(IndexController::class);
        $logger->info('Init in Module');


        if ($config->get('enabled')) {
            $logger->info('enable module');
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
        } else {
            $logger->info('disable module');
        }
    }
}
