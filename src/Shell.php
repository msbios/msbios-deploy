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
        //if (isset($options['shell']) && $options['shell']) {
        //    /** @var string $result */
        //    $result = shell_exec($command);
        //    file_put_contents(FILENAME, "$result\r\n");
        //    $output[] = $result;
        //    continue;
        //}
    }

    /**
     * @param array $options
     * @return Shell
     */
    public static function factory(array $options) {
        return new self($options);
    }
}