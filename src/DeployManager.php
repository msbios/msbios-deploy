<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Monolog\LoggerManagerInterface;

/**
 * Class DeployManager
 * @package MSBios\Deploy
 */
class DeployManager implements DeployManagerInterface
{
    /** @var AdapterInterface */
    protected $adapter;

    /** @var LoggerManagerInterface */
    protected $logger;

    /** @var string */
    protected $token;

    /**
     * DeployManager constructor.
     * @param AdapterInterface $adapter
     * @param LoggerManagerInterface $loggerManager
     */
    public function __construct(AdapterInterface $adapter, LoggerManagerInterface $loggerManager)
    {
        $this->adapter = $adapter;
        $this->logger = $loggerManager;
    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function verify()
    {
        return $this->adapter->identity() == $this->token;
    }
}