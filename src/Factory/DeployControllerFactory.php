<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Deploy\DeployManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DeployControllerFactory
 * @package MSBios\Deploy\Factory
 */
class DeployControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return (new $requestedName())
            ->setDeployManager($container->get(DeployManager::class));
    }
}
