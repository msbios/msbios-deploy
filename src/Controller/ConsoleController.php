<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\DeployManagerAwareInterface;
use MSBios\Deploy\DeployManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ConsoleController
 * @package MSBios\Deploy\Controller
 */
class ConsoleController extends AbstractActionController implements DeployManagerAwareInterface
{
    use DeployManagerAwareTrait;

    /**
     * @return void|\Zend\View\Model\ViewModel
     * @throws \MSBios\Deploy\Exception\Exception
     */
    public function indexAction()
    {
        echo "Start Deployment.\n";
        /** @var string $output */
        $output = $this->deployManager->run();
        echo "{$output}\n";
        echo "Deployment was successful.\n";
    }
}
