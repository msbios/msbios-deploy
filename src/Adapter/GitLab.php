<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\Adapter;

use MSBios\Deploy\AdapterInterface;
use MSBios\Deploy\DeployManagerInterface;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Header\HeaderInterface;
use Zend\Http\Headers;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class GitLab
 * @package MSBios\Deploy\Adapter
 */
class GitLab implements AdapterInterface
{
    /** @var  Headers */
    protected $headers;

    /**
     * GitLab constructor.
     * @param Headers $headers
     */
    public function __construct(Headers $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function identity()
    {
        /** @var HeaderInterface|GenericHeader $genericHeader */
        $genericHeader = $this->headers->get('X-Gitlab-Token');
        return $genericHeader->getFieldValue();
    }

    /**
     * @param DeployManagerInterface $deployManager
     * @param array|null $data
     */
    public function report(DeployManagerInterface $deployManager, array $data = null)
    {
        /** @var array $output */
        $output = $deployManager->getOutput();

        /** @var Message $mail */
        $mail = new Message;
        $mail->setBody(implode("\r\n", $output))
            ->setFrom('msbios@gmail.com', self::class)
            ->setTo($data['user_email'], $data['user']['name'])
            ->setSubject("inf - Deploy Project {$data['project']['name']}");

        /** @var TransportInterface $transport */
        $transport = new Sendmail('-freturn_to_me@example.com');
        $transport->send($mail);
    }
}