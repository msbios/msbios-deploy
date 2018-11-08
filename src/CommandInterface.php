<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Exception\Exception;

/**
 * Interface CommandInterface
 * @package MSBios\Deploy
 */
interface CommandInterface
{
    /**
     * @param array|null $data
     * @return mixed
     * @throws Exception
     */
    public function run(array $data = null);
}
