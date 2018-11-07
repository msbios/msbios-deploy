<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\DeployManagerInterface;
use MSBios\Deploy\Exception\Exception;
use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\MessageInterface;
use Zend\View\Model\JsonModel;

/**
 * Class IndexController
 * @package MSBios\Deploy\Controller
 */
class IndexController extends AbstractRestfulController
{
    /** @const */
    const COMMAND_FORMAT = "%bash% pull origin %branch% 2>&1";

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
            $this->response->setStatusCode(Response::STATUS_CODE_403);
            return new JsonModel([
                'success' => false,
                'message' => 'Access Denied.'
            ]);
        }

        /** @var array $response */
        $data = ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON))
            ? $this->jsonDecode($request->getContent())
            : $request->getPost()->toArray();

        $this->deployManager->run($data);

        return new JsonModel([
            'success' => true,
            'message' => 'Deployment was successful.'
        ]);
    }
}
