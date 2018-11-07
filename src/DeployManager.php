<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Exception\Exception;
use MSBios\Deploy\Exception\InvalidArgumentException;
use MSBios\Monolog\LoggerManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

/**
 * Class DeployManager
 * @package MSBios\Deploy
 */
class DeployManager implements DeployManagerInterface
{
    /** @var EventManagerInterface */
    protected $eventManager;

    /** @var AdapterInterface */
    protected $adapter;

    /** @var LoggerManagerInterface */
    protected $logger;

    /** @var string */
    protected $token;

    /** @var  array */
    protected $commands;

    /** @const EVENT_REPORT */
    const EVENT_REPORT = 'EVENT_REPORT';

    /** @const EVENT_REPORT_ERROR */
    const EVENT_REPORT_ERROR = 'EVENT_REPORT_ERROR';

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
     * @return EventManager|EventManagerInterface
     */
    public function getEventManager()
    {
        if (is_null($this->eventManager)) {
            $this->eventManager = new EventManager;
        }

        return $this->eventManager;
    }

    /**
     * @param $eventManager
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
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

    /**
     * @param CommandInterface $command
     * @return $this
     */
    public function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;
        return $this;
    }

    /**
     * @param array $commands
     * @return $this
     */
    public function addCommands(array $commands)
    {

        /** @var array $command */
        foreach ($commands as $command) {
            if (! isset($command['type'])) {
                throw new InvalidArgumentException('Missing "type" option');
            }

            /** @var string $commandName */
            $commandName = $command['type'];

            //if (! class_exists($commandName)) {
            //    throw new ServiceNotCreatedException(sprintf(
            //        '%s: failed retrieving invokable class "%s"; class does not exist',
            //        __CLASS__,
            //        $routeName
            //    ));
            //}

            //if (! is_subclass_of($routeName, RouteInterface::class)) {
            //    throw new ServiceNotCreatedException(sprintf(
            //        '%s: failed retrieving invokable class "%s"; class does not implement %s',
            //        __CLASS__,
            //        $routeName,
            //        RouteInterface::class
            //    ));
            //}

            /** @var CommandInterface $instance */
            $instance = $commandName::factory($command['options']);
            $this->addCommand($instance);
        }

        return $this;
    }

    /**
     * @param array|null $data
     * @return string
     */
    public function run(array $data = null)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = $this->getEventManager();

        try {

            /** @var array $output */
            $output = [];

            /** @var CommandInterface $command */
            foreach ($this->commands as $command) {
                $output[] = $command->run($data);
            }

            $eventManager->trigger(self::EVENT_REPORT, [
                'deploy' => $this,
                'message' => implode("\r\n", $output) ,
                'data' => $data
            ]);
        } catch (Exception $exception) {
            $eventManager->trigger(self::EVENT_REPORT_ERROR, [
                'deploy' => $this,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
