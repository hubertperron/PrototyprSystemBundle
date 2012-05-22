<?php

namespace Prototypr\SystemBundle\EntityRouteMap;

use Doctrine\Common\Util\Inflector;

use Prototypr\SystemBundle\Entity\Page;
use Prototypr\SystemBundle\EntityRouteMap\BaseMap;

class BundleMap extends BaseMap
{
    /**
     * Incoming options:
     *
     * - parameters
     * - suffix
     * - application
     * - page
     * - page_application
     *
     * @param array $options
     *
     * @return string
     */
    public function getRoute($options)
    {
        $bundleRoutingName = str_replace('prototypr_', '', Inflector::tableize($this->entity->getClass()));
        $application = $options['application'] ?: $this->getCurrentApplicationName();

        if ($options['page']) {
            $pageApplication = $options['page']->getApplication()->getName();
            $pageId = $options['page']->getId();
        } else {
            $pageApplication = $options['page_application'] ?: $this->getCurrentApplicationName();
            $pageId = '\d+';
        }

        foreach ($this->getRouteCollection() as $name => $route) {
            if (preg_match('/.+' . $pageApplication . '_page_' . $pageId .'_' . $bundleRoutingName . '_' . $application . '.*/', $name)) {
                return $name . $options['suffix'];
            }
        }
    }

}