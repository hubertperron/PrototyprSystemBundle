<?php

namespace Prototypr\SystemBundle\Twig;

use Prototypr\SystemBundle\Exception\SystemNotInitializedException;
use Prototypr\SystemBundle\Core\SystemKernel;

/**
 * Prototypr core twig extensions
 *
 * Inspired by: http://www.kamiladryjanek.com/2011/11/symfony2-get-controller-action-name-in-twig-template/
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
            'action_name' => $this->getActionName(),
            'application_name' => $this->getApplicationName(),
            'controller_name' => $this->getControllerName(),
            'bundle_name' => $this->getBundleName(),
//            'node' => 'CURRENT_node_TODO',
//            'page' => 'CURRENT_page_TODO',
        );
    }

    /**
     * Get current action name
     *
     * input:   Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction
     * output:  index
     *
     * @return string
     * @throws SystemNotInitializedException
     */
    public function getActionName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            throw new SystemNotInitializedException();
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
     * @throws SystemNotInitializedException
     */
    public function getApplicationName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            throw new SystemNotInitializedException();
        }

        if ($application = $this->systemKernel->getMasterRequest()->get('_prototypr_application')) {
            return $application;
        }
    }

    /**
     * Get the current bundle name
     *
     * @return string
     * @throws SystemNotInitializedException
     */
    public function getBundleName()
    {
        if (false == $this->systemKernel->getMasterRequest()) {
            throw new SystemNotInitializedException();
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
            throw new SystemNotInitializedException();
        }

        $pattern = '#Controller\\\([a-zA-Z\\\]*)Controller#';
        $matches = array();

        if (preg_match($pattern, $this->systemKernel->getMasterRequest()->get('_controller'), $matches)) {
            return str_replace('\\', '_', strtolower($matches[1]));
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
