<?php

namespace Prototypr\SystemBundle\EntityRouteMap;

use Doctrine\Common\Util\Inflector;

use Prototypr\SystemBundle\Entity\Page;
use Prototypr\SystemBundle\EntityRouteMap\BaseMap;

class PageBundleMap extends BaseMap
{
    /**
     * Incoming options:
     *
     * - parameters
     * - suffix
     * - application (automatically overridden)
     * - page (automatically overridden)
     * - page_application (automatically overridden)
     *
     * @param array $options
     *
     * @return string
     */
    public function getRoute($options)
    {
        $bundleRoutingName = str_replace('prototypr_', '', Inflector::tableize($this->entity->getBundle()->getClass()));

        $pageApplication = $this->entity->getPage()->getApplication()->getName();
        $pageId = $this->entity->getPage()->getId();
        $application =  $this->entity->getBundleApplication() ?: $this->entity->getApplication()->getName();

        foreach ($this->getRouteCollection() as $name => $route) {
            if (preg_match('/.+' . $pageApplication . '_page_' . $pageId . '_' . $bundleRoutingName . '_' . $application . '.*/', $name)) {
                return $name . $options['suffix'];
            }
        }
    }

}