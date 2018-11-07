<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\DeployManagerInterface;
use MSBios\Deploy\Exception\InvalidArgumentException;
use MSBios\Deploy\InputFilter\DispatchInputFilter;
use Psr\Log\LoggerInterface;
use Zend\Config\Config;
use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Request;
use Zend\InputFilter\InputFilterInterface;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\MessageInterface;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

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
        if (!$this->deployManager->verify()) {
            $this->response->setStatusCode(Response::STATUS_CODE_403);
            return new JsonModel([
                'success' => false,
                'message' => 'Access Denied.'
            ]);
        }

        /** @var MessageInterface $request */
        $request = $this->getRequest();

        /** @var array $response */
        $data = ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON))
            ? $this->jsonDecode($request->getContent())
            : $request->getPost()->toArray();

        $this->deployManager->run($data);

        return $this->notFoundAction();

        ///** @var string $token */
        //$token = $this->params()->fromQuery('token');
        //
        //if (! $token || $token != $this->options->get('token')) {
        //    $this->response->setStatusCode(Response::STATUS_CODE_403);
        //    return new JsonModel([
        //        'success' => false,
        //        'message' => 'Access Denied.'
        //    ]);
        //}
        //
        ///** @var RequestInterface $request */
        //$request = $this->getRequest();
        //
        ///** @var array $response */
        //$data = ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON))
        //    ? $this->jsonDecode($request->getContent())
        //    : $request->getPost()->toArray();
        //
        ///** @var InputFilterInterface $inputFilter */
        //$inputFilter = new DispatchInputFilter; // TODO: Move To ServiceLocator
        //
        ///** @var array $values */
        //$values = $inputFilter->setData($data)->getValues();
        //
        //try {
        //    if ($this->options->get('branch') != $values['ref']) {
        //        throw new InvalidArgumentException(
        //            "The requested branch for updating is not equal to the code branch on the server"
        //        );
        //    }
        //
        //    /** @var string $command */
        //    $command = str_replace([
        //        '%bash%', '%branch%'
        //    ], [
        //        $this->options->get('git'), $this->options->get('branch')
        //    ], self::COMMAND_FORMAT);
        //
        //    exec($command, $output, $code);
        //
        //    /** @var string $item */
        //    foreach ($output as $item) {
        //        $this->logger->info($item);
        //    }
        //
        //    /** @var string $filename */
        //    $filename = realpath('deploy.sh');
        //
        //    if (file_exists($filename)) {
        //
        //        /** @var string $output */
        //        exec("bash " . $filename, $output);
        //
        //        /** @var string $item */
        //        foreach ($output as $item) {
        //            $this->logger->info($item);
        //        }
        //    }
        //
        //    if ($code !== 0) {
        //        throw new InvalidArgumentException(
        //            "500 Internal Server Error on script execution. Check <VirtualHost> config or nginx.service status"
        //        );
        //    }
        //} catch (InvalidArgumentException $ex) {
        //
        //    /** @var string $msg */
        //    $msg = $ex->getMessage();
        //
        //    $this->response->setStatusCode(Response::STATUS_CODE_500);
        //    $this->logger->debug(Json::encode($values));
        //    $this->logger->error($msg);
        //
        //    return new JsonModel([
        //        'success' => false,
        //        'message' => $msg
        //    ]);
        //}
        //
        ///** @var ModelInterface $return */
        //return new JsonModel([
        //    'success' => true,
        //    'message' => 'Success Response.'
        //]);
    }
}
