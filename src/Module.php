<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Controller\IndexController;
use MSBios\ModuleInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Router\Http\Literal;
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

        /** @var Literal $route */
        $route = Literal::factory([
            'route' => $serviceLocator->get(self::class)->get('url'),
            'defaults' => [
                'controller' => IndexController::class,
                'action' => 'index',
            ],
        ]);

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $e->getTarget()->getServicemanager();
        $serviceLocator->get('Router')->addRoute('auto-deploy', $route);
    }
}
