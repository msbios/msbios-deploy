<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Exception\Exception;

/**
 * Class Command
 * @package MSBios\Deploy
 */
class Command implements CommandInterface
{
    /** @var mixed */
    protected $command;

    /** @var string */
    protected $name;

    /**
     * Command constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->command = $options['command'];
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return !empty($this->name)
            ? $this->name : $this->command;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        $this->name = $name;
        return $name;
    }

    /**
     * @param array|null $data
     * @return string
     * @throws Exception
     */
    public function run(array $data = null)
    {
        /** @var null $output */
        $output = $return_var = null;
        exec($this->command, $output, $return_var);

        if (0 !== $return_var) {
            throw new Exception("Something happened wrong with '{$this->getName()}'\r\n");
        }

        return implode("\r\n", $output);
    }

    /**
     * @param array $options
     * @return Command
     */
    public static function factory(array $options)
    {
        return new self($options);
    }
}
