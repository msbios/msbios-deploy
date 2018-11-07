<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\DeployManagerInterface;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\MessageInterface;
use Zend\View\Model\JsonModel;

/**
 * Class IndexController
 * @package MSBios\Deploy\Controller
 */
class IndexController extends AbstractActionController
{
    /** @var  DeployManagerInterface */
    protected $deployManager;

    /**
     * IndexController constructor.
     * @param DeployManagerInterface $deployManager
     */
    public function __construct(DeployManagerInterface $deployManager)
    {
        $this->deployManager = $deployManager;
    }

    /**
     * @return JsonModel
     */
    public function indexAction()
    {
        /** @var MessageInterface|Request $request */
        $request = $this->getRequest();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        if (! $this->deployManager->verify()) {
            return new JsonModel([
                'success' => false,
                'message' => 'Verified token is not allowed. Access Denied'
            ]);
        }

        // /** @var array $response */
        // $data = ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON))
        //     ? $this->jsonDecode($request->getContent())
        //     : $request->getPost()->toArray();

        /** @var array $data */
        $data = json_decode(file_get_contents('php://input'), true);

        $this->deployManager->run($data);

        return new JsonModel([
            'success' => true,
            'message' => 'Deployment was successful.'
        ]);
    }
}
