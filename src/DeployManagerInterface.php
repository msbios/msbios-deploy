<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy;

/**
 * Interface DeployManagerInterface
 * @package MSBios\Deploy
 */
interface DeployManagerInterface extends CommandInterface
{
    /**
     * @return mixed
     */
    public function setToken($token);

    /**
     * @return mixed
     */
    public function verify();
}
