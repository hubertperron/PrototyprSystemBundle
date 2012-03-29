<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Prototypr\SystemBundle\Entity\Base;

/**
 * Prototypr\SystemBundle\Entity\Node
 */
abstract class Node extends Base
{

    /**
     * @var string $headers
     */
    protected $headers;

    /**
     * @var string $title
     */
    protected $title;


    /**
     * Set headers
     *
     * @param string $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get headers
     *
     * @return string 
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}