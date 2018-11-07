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

    /**
     * Command constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->command = $options['command'];
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
            header("{$_SERVER['SERVER_PROTOCOL']} 500 Internal Server Error", true, 500);
            throw new Exception("Something happened wrong with '{$this->command}'\r\n");
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