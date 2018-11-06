<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

/**
 * Class DeployManager
 * @package MSBios\Deploy
 */
class DeployManager
{
    /** @var AdapterInterface */
    protected $adapter;

    /**
     * DeployManager constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}