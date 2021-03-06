<?php

namespace Prototypr\SystemBundle\Twig;

use Prototypr\SystemBundle\Exception\SystemNotInitializedException;
use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;

/**
 * Prototypr core twig extensions
 *
 * Inspired by: http://www.kamiladryjanek.com/2011/11/symfony2-get-controller-action-name-in-twig-template/
 */
class UtilityExtension extends \Twig_Extension
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
            'action_name' => $this->getActionName(),
            'application_name' => $this->getApplicationName(),
            'controller_name' => $this->getControllerName(),
            'bundle_name' => $this->getBundleName(),
            'system_kernel' => $this->getSystemKernel(),
            'application_kernel' => $this->getApplicationKernel()
        );
    }

    /**
     * Get current action name
     *
     * input:   Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction
     * output:  index
     *
     * @return string
     */
    public function getActionName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            return;
        }

        $pattern = "#::([a-zA-Z]*)Action#";
        $matches = array();

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return strtolower($matches[1]);
        }
    }

    /**
     * Get the current application name
     *
     * @return string
     */
    public function getApplicationName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            return;
        }

        if ($application = $this->systemKernel->getMasterRequest()->get('_prototypr_application')) {
            return $application;
        }
    }

    /**
     * Get the current bundle name
     *
     * input:   Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction
     * output:  news
     *
     * @return string
     */
    public function getBundleName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            return;
        }

        $pattern = '#\\\([a-zA-Z]*)Bundle#';
        $matches = array();

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return strtolower($matches[1]);
        }
    }

    /**
     * Get current controller name in a shortcut format
     *
     * input:   Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction
     * output:  frontend_news
     *
     * @return string
     * @throws SystemNotInitializedException
     */
    public function getControllerName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            return;
        }

        $pattern = '#Controller\\\([a-zA-Z\\\]*)Controller#';
        $matches = array();

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return str_replace('\\', '_', strtolower($matches[1]));
        }
    }

    /**
     * Get the system kernel
     *
     * @return SystemKernel
     */
    public function getSystemKernel()
    {
        return $this->systemKernel;
    }

    /**
     * Get the currently loaded application kernel
     *
     * @return ApplicationKernel
     */
    public function getApplicationKernel()
    {
       return $this->getSystemKernel()->getApplicationKernel();
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
