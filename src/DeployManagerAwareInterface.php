<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy;

/**
 * Interface DeployManagerAwareInterface
 * @package MSBios\Deploy
 */
interface DeployManagerAwareInterface
{
    /**
     * @return DeployManagerInterface
     */
    public function getDeployManager(): DeployManagerInterface;

    /**
     * @param DeployManagerInterface $deployManager
     * @return $this
     */
    public function setDeployManager(DeployManagerInterface $deployManager);
}
