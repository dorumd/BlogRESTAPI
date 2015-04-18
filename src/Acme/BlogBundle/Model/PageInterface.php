<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 7:22 PM
 */

namespace Acme\BlogBundle\Model;

interface PageInterface
{
    /**
     * Set title
     *
     * @param string $title
     *
     * @return PageInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set body
     *
     * @param string $body
     *
     * @return PageInterface
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();
}