<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

/**
 * Interface CommandInterface
 * @package MSBios\Deploy
 */
interface CommandInterface
{
    /**
     * @param array|null $data
     * @return mixed
     */
    public function run(array $data = null);
}