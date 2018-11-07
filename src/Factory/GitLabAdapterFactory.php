<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Deploy\Adapter\GitLab;
use Zend\Http\Request;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Stdlib\MessageInterface;

/**
 * Class GitLabAdapterFactory
 * @package MSBios\Deploy\Factory
 */
class GitLabAdapterFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GitLab
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var MessageInterface|Request $request */
        $request = $container->get('Request');
        return new GitLab($request->getHeaders());
    }
}
