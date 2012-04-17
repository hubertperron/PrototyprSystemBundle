<?php

namespace Prototypr\SystemBundle\Twig;

use Prototypr\SystemBundle\Exception\SystemNotInitializedException;
use Prototypr\SystemBundle\Core\SystemKernel;

/**
 * Prototypr core twig extensions
 */
class Extension extends \Twig_Extension
{
    /**
     * @var SystemKernel
     */
    protected $systemKernel;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * Init Runtime
     *
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Construct
     *
     * @param SystemKernel $systemKernel
     */
    public function __construct($systemKernel)
    {
        $this->systemKernel = $systemKernel;
    }

    /**
     * Get global variables
     *
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'controller_name' => $this->getControllerName(),
            'action_name' => $this->getActionName(),
            'bundle_name' => 'BUNDLE_NAME_TODO',
            'element' => 'CURRENT_ELEMENT_TODO',
        );
    }

    /**
     * Get current controller name
     *
     * @return string
     * @throws SystemNotInitializedException
     */
    public function getControllerName()
    {
        $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
        $matches = array();

        if (false == $this->systemKernel->getMasterRequest()) {
            throw new SystemNotInitializedException();
        }

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return strtolower($matches[1]);
        }
    }

    /**
     * Get current action name
     *
     * @return string
     * @throws SystemNotInitializedException
     */
    public function getActionName()
    {
        $pattern = "#::([a-zA-Z]*)Action#";
        $matches = array();

        if (false == $this->systemKernel->getMasterRequest()) {
            throw new SystemNotInitializedException();
        }

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return strtolower($matches[1]);
        }
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'prototypr_system';
    }

}
