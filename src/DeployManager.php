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
use Zend\Http\PhpEnvironment\Response;

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

    /** @const EVENT_VERIFY */
    const EVENT_VERIFY = 'EVENT_VERIFY';

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
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     * @return DeployManagerInterface
     */
    public function setAdapter(AdapterInterface $adapter): DeployManagerInterface
    {
        $this->adapter = $adapter;
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
        /** @var string $identity */
        $identity = $this->adapter->identity();

        /** @var boolean $result */
        $result = ($identity == $this->token);

        $this->getEventManager()->trigger(self::EVENT_VERIFY, $this, [
            'message' => implode("\r\n", [
                "Verified information:",
                sprintf("Adapter identifier information: %s", $identity),
                sprintf("Configuration token: %s", $this->token),
                sprintf("Result of checking: %s", $result),
            ])
        ]);

        return $result;
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
     * @return array|mixed
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

            /** @var array $argv */
            $argv = [
                'message' => implode("\r\n", $output) ,
                'data' => $data
            ];

            $eventManager->trigger(self::EVENT_REPORT, $this, $argv);

            return $argv['message'];
        } catch (Exception $exception) {
            header("500 Internal Server Error", true, Response::STATUS_CODE_500);
            $eventManager->trigger(self::EVENT_REPORT_ERROR, $this, [
                'message' => $exception->getMessage(),
                'data' => $data
            ]);
        }
    }
}
