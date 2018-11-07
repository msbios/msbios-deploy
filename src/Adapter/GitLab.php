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
}
