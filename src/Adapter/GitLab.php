<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Deploy\Adapter;

use MSBios\Deploy\AdapterInterface;
use Zend\Console\Console;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Header\HeaderInterface;
use Zend\Stdlib\MessageInterface;

/**
 * Class GitLab
 * @package MSBios\Deploy\Adapter
 */
class GitLab implements AdapterInterface
{

    const DEFAULT_KEY_NAME = 'X-Gitlab-Token';

    /** @var  MessageInterface|ConsoleRequest */
    protected $request;

    /**
     * GitLab constructor.
     * @param MessageInterface $request
     */
    public function __construct(MessageInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool|mixed|string
     */
    public function identity()
    {
        if (! Console::isConsole()) {
            /** @var HeaderInterface|GenericHeader $genericHeader */
            $genericHeader = $this->request
                ->getHeaders()
                ->get(self::DEFAULT_KEY_NAME);

            return $genericHeader->getFieldValue();
        }

        return $this->request
            ->getParam(self::DEFAULT_KEY_NAME);
    }
}
