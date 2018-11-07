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
     * @return bool
     */
    public function identity();

    /**
     * @param DeployManagerInterface $deployManager
     * @param array|null $data
     * @return mixed
     */
    public function report(DeployManagerInterface $deployManager, array $data = null);
}