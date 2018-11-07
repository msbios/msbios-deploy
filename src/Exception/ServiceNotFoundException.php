<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Exception;

use Zend\ServiceManager\Exception\ServiceNotFoundException as DefaultServiceNotFoundException;

/**
 * Class ServiceNotFoundException
 * @package MSBios\Deploy\Exception
 */
class ServiceNotFoundException extends DefaultServiceNotFoundException
{
    // ...
}