<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy;

/**
 * Interface AdapterInterface
 * @package MSBios\Deploy
 */
interface AdapterInterface
{
    /**
     * @return mixed
     */
    public function verify();
}