<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 7:26 PM
 */

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Entity\Page;
use Acme\BlogBundle\Model\PageInterface;

/**
 * Interface PageHandlerInterface
 *
 * @package Acme\BlogBundle\Handler
 */
interface PageHandlerInterface
{
    /**
     * Get a Page given identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return PageInterface
     */
    public function get($id);

    /**
     * Post page, creates a new page
     *
     * @param array $parameters
     *
     * @return PageInterface
     */
    public function post(array $parameters);

    /**
     * Put page, updates existing one
     *
     * @param PageInterface $page
     * @param array         $parameters
     *
     * @return PageInterface
     */
    public function put(PageInterface $page, array $parameters);

    /**
     * Partially update existing page
     *
     * @param PageInterface $page
     * @param array         $parameters
     *
     * @return PageInterface
     */
    public function patch(PageInterface $page, array $parameters);

    /**
     * List of pages
     *
     * @param int $limit
     * @param int $offset
     *
     * @return mixed
     */
    public function all($limit, $offset);
}