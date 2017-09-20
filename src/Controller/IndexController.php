<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Deploy\Controller;

use MSBios\Deploy\InputFilter\DispatchInputFilter;
use Psr\Log\LoggerInterface;
use Zend\Config\Config;
use Zend\Http\PhpEnvironment\Response;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

/**
 * Class IndexController
 * @package MSBios\Deploy\Controller
 */
class IndexController extends AbstractRestfulController
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var Config */
    protected $options;

    /**
     * IndexController constructor.
     * @param LoggerInterface $logger
     * @param Config $options
     */
    public function __construct(LoggerInterface $logger, Config $options)
    {
        $this->logger = $logger;
        $this->options = $options;
    }

    /**
     * @return JsonModel|ModelInterface
     */
    public function dispatchAction()
    {
        /** @var string $token */
        $token = $this->params()->fromQuery('token');

        if (! $token || $token != $this->options->get('token')) {
            $this->response->setStatusCode(Response::STATUS_CODE_403);
            return new JsonModel([
                'success' => false,
                'message' => 'Access Denied.'
            ]);
        }

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        /** @var array $response */
        $data = ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON))
            ? $this->jsonDecode($request->getContent())
            : $request->getPost()->toArray();

        /** @var InputFilterInterface $inputFilter */
        $inputFilter = new DispatchInputFilter; // TODO: Move To ServiceLocator

        /** @var array $values */
        $values = $inputFilter->setData($data)->getValues();

        if ($this->options->get('branch') == $values['ref']) {
            /** @var string $command */
            $command = $this->options->get('git')
                . ' pull origin '
                . $this->options->get('branch')
                . ' 2>&1';

            exec($command, $output, $code);

            /** @var string $item */
            foreach ($output as $item) {
                $this->logger->info($item);
            }

            if ($code !== 0) {

                $this->response->setStatusCode(Response::STATUS_CODE_500);
                $this->logger->error("500 Internal Server Error on script execution. Check <VirtualHost> config or nginx.service status");

                return new JsonModel([
                    'success' => false,
                    'message' => '500 Internal Server Error.'
                ]);
            } else {
                // $sh_exec = shell_exec("bash deploy.sh 2>&1");
                // logger($web_path, "Executing deploy.sh script...." . $sh_exec . "\r\n");
                // logger($web_path, "*** AUTO PULL SUCCESFUL ***" . "\n");

                //if ($replybymail == true) {
                //    $user_name = $json['user_name'];
                //    $user_mail = $json['user_email'];
                //    $project_name = $json['project']['name'];
                //    $subject = 'inf deploy project ' . $project_name;
                //    $message = implode("\n", $output);
                //    $message .= $sh_exec;
                //    if (mail($user_mail, "inf deploy project " . $project_name, $message)) {
                //        logger($web_path, "Mail send success!");
                //    } else {
                //        logger($web_path, "Mail send error!");
                //    }
                //}
            }
        }

        /** @var ModelInterface $return */
        return new JsonModel([
            'success' => true,
            'message' => 'Success Response.'
        ]);
    }
}
