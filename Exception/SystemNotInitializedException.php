<?php

namespace Prototypr\SystemBundle\Exception;

/**
 * System not initialized exception.
 * This is thrown when trying to access core functionnalities
 * outside of a compatible request
 */
class SystemNotInitializedException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $this->message = $message ?: 'Prototypr is not initialized. The controller class is not extending prototypr base controller or the matched route is missing some parameters. See <abbr title="Prototypr\SystemBundle\Listener">ControllerListener->isPrototyprEnabled()</abbr> for more details.';
    }
}
