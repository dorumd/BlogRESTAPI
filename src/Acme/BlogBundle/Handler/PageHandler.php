<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 7:19 PM
 */

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Form\PageType;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Model\PageInterface;
use Acme\BlogBundle\Entity\Page;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Exception\InvalidFormException;

/**
 * Class PageHandler
 *
 * @package Acme\BlogBundle\Handler
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class PageHandler implements PageHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    /**
     * @param ObjectManager        $om
     * @param Page                 $entityClass
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        ObjectManager $om,
        $entityClass,
        FormFactoryInterface $formFactory
    )
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a list of pages
     *
     * @param int  $limit
     * @param int  $offset
     * @param null $orderBy
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0, $orderBy = null)
    {
        return $this->repository->findBy([], $orderBy, $limit, $offset);
    }

    /**
     * Get a page
     *
     * @param mixed $id
     *
     * @return PageInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new page
     *
     * @param array $parameters
     *
     * @return PageInterface
     */
    public function post(array $parameters)
    {
        $page = $this->createPage();

        return $this->processForm($page, $parameters, 'POST');
    }

    /**
     * Edit a Page, or create if not exists
     *
     * @param PageInterface $page
     * @param array         $parameters
     *
     * @return PageInterface|mixed
     */
    public function put(PageInterface $page, array $parameters)
    {
        return $this->processForm($page, $parameters, 'PUT');
    }

    /**
     * Partially update a page
     *
     * @param PageInterface $page
     * @param array         $parameters
     *
     * @return PageInterface|mixed
     */
    public function patch(PageInterface $page, array $parameters)
    {
        return $this->processForm($page, $parameters, 'PATCH');
    }

    /**
     * Process the form
     *
     * @param PageInterface $page
     * @param array         $parameters
     * @param string        $method
     *
     * @return PageInterface|mixed
     */
    private function processForm(PageInterface $page, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PageType(), $page, array('method' => $method));
        $form->bind($parameters);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($page);
            $this->om->flush($page);

            return $page;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createPage()
    {
        return new $this->entityClass();
    }
}