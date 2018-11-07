<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy;

/**
 * Class Shell
 * @package MSBios\Deploy
 */
class Shell implements CommandInterface
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
     * @var array
     */
    protected $output = [];

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param array|null $data
     */
    public function run(array $data = null)
    {
        /** @var string $result */
        return shell_exec($this->command);
    }

    /**
     * @param array $options
     * @return Shell
     */
    public static function factory(array $options) {
        return new self($options);
    }
}