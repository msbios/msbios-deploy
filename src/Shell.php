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
class Shell extends Command
{
    /**
     * @param array|null $data
     */
    public function run(array $data = null)
    {
        /** @var string $result */
        return shell_exec($this->command);
    }
}
