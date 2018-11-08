<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\DeployManagerAwareInterface;
use MSBios\Deploy\DeployManagerAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\MessageInterface;
use Zend\View\Model\JsonModel;

/**
 * Class HttpController
 * @package MSBios\Deploy\Controller
 */
class HttpController extends AbstractRestfulController implements DeployManagerAwareInterface
{
    use DeployManagerAwareTrait;

    /**
     * @return array|JsonModel
     * @throws \MSBios\Deploy\Exception\Exception
     */
    public function indexAction()
    {
        /** @var MessageInterface|Request $request */
        $request = $this->getRequest();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $this->deployManager
            ->getAdapter()
            ->setRequest($request);

        if (! $this->deployManager->verify()) {
            return new JsonModel([
                'success' => false,
                'message' => 'Verified token is not allowed. Access Denied'
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
