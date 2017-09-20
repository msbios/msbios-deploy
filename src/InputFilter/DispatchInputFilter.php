<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class DispatchInputFilter
 * @package MSBios\Deploy\InputFilter
 */
class DispatchInputFilter extends InputFilter
{
    /**
     * DispatchInputFilter constructor.
     */
    public function __construct()
    {
        $this->add([
            'name' => 'ref'
        ])->add([
            'name' => 'user_name'
        ])->add([
            'name' => 'user_email'
        ]);
    }

}