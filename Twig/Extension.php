<?php

namespace Prototypr\SystemBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

/**
 * Prototypr core twig extensions
 */
class Extension extends \Twig_Extension
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * __construct
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
     */
    public function getControllerName()
    {
        $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
        $matches = array();

        if (preg_match($pattern, $this->request->get('_controller'), $matches)) {
            return strtolower($matches[1]);
        }
    }

    /**
     * Get current action name
     *
     * @return string
     */
    public function getActionName()
    {
        $pattern = "#::([a-zA-Z]*)Action#";
        $matches = array();

        if (preg_match($pattern, $this->request->get('_controller'), $matches)) {
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
