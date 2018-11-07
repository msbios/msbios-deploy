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
    /**
     * @var
     */
    protected $command;

    /**
     * @var
     */
    protected $output;

    /**
     * Command constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->command = $options['command'];
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param array|null $data
     * @throws Exception
     */
    public function run(array $data = null)
    {
        /** @var integer $return_var */
        $return_var = null;
        exec($this->command, $this->output, $return_var);

        var_dump([$this->output, $return_var]); die();

        if (0 !== $return_var) {
            header("{$_SERVER['SERVER_PROTOCOL']} 500 Internal Server Error", true, 500);
            throw new Exception("Something happened wrong with '{$this->command}'\r\n");
        }
    }

    /**
     * @param array $options
     * @return Command
     */
    public static function factory(array $options) {
        return new self($options);
    }
}