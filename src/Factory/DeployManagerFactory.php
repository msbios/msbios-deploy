<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Deploy\AdapterInterface;
use MSBios\Deploy\DeployManager;
use MSBios\Deploy\DeployManagerInterface;
use MSBios\Deploy\Exception\ServiceNotFoundException;
use MSBios\Deploy\Module;
use MSBios\Monolog\LoggerManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DeployManagerFactory
 * @package MSBios\Deploy\Factory
 */
class DeployManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DeployManager|DeployManagerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get(Module::class);

        if (! $container->has($config['adapter'])) {
            throw new ServiceNotFoundException(
                sprintf('Service adapter with name %s not found!', $config['adapter'])
            );
        }

        /** @var AdapterInterface $adapter */
        $adapter = $container->get($config['adapter']);

        /** @var DeployManagerInterface|DeployManager $deployManager */
        $deployManager = new DeployManager(
            $adapter,
            $container->get(LoggerManager::class)
        );

        $deployManager
            ->addCommands($config['commands'])
            ->setToken($config['token']);

        return $deployManager;
    }
}
