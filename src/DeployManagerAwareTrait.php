<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy;

/**
 * Trait DeployManagerAwareTrait
 * @package MSBios\Deploy
 */
trait DeployManagerAwareTrait
{
    /** @var DeployManagerInterface */
    protected $deployManager;

    /**
     * @return DeployManagerInterface
     */
    public function getDeployManager(): DeployManagerInterface
    {
        return $this->deployManager;
    }

    /**
     * @param DeployManagerInterface $deployManager
     * @return $this
     */
    public function setDeployManager(DeployManagerInterface $deployManager)
    {
        $this->deployManager = $deployManager;
        return $this;
    }
}
