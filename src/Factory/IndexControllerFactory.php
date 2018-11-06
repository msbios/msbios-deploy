<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Deploy\Controller\IndexController;
use MSBios\Deploy\Module;
use MSBios\Monolog\LoggerManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 * @package MSBios\Deploy\Factory
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(LoggerManager::class)->get($requestedName),
            $container->get(Module::class)->get('deploy')
        );
    }
}
