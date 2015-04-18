<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 7:33 PM
 */

namespace Acme\BlogBundle\Tests\Handler;

use Acme\BlogBundle\Handler\PageHandler;
use Acme\BlogBundle\Entity\Page;

/**
 * Class PageHandlerTest
 *
 * @package Acme\BlogBundle\Tests\Handler
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class PageHandlerTest extends \PHPUnit_Framework_TestCase
{
    const PAGE_CLASS = 'Acme\BlogBundle\Tests\Handler\DummyPage';

    /** @var PageHandler */
    protected $pageHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    /**
     * Set up
     */
    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine common has to be installed for this test to run');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::PAGE_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTO(static::PAGE_CLASS))
            ->will($this->returnValue($class));
        $this->om->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::PAGE_CLASS));
    }

    /**
     * Test get
     */
    public function testGet()
    {
        $id = 1;
        $page = $this->getPage();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($page));

        $this->pageHandler = $this->createPageHandler($this->om, static::PAGE_CLASS, $this->formFactory);
        $this->pageHandler->get($id);
    }

    /**
     * Test post
     */
    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = ['title' => $title, 'body' => $body];

        $page = $this->getPage();
        $page
            ->setTitle($title)
            ->setBody($body);

        $form = $this->getMock('Acme\BlogBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($page));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->pageHandler = $this->createPageHandler($this->om, static::PAGE_CLASS, $this->formFactory);
        $pageObject = $this->pageHandler->post($parameters);

        $this->assertEquals($pageObject, $page);
    }

    /**
     * Test post should raise exception
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = ['title' => $title, 'body' => $body];

        $page = $this->getPage();
        $page
            ->setBody($body)
            ->setTitle($title);

        $form = $this->getMock('Acme\BlogBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->pageHandler = $this->createPageHandler($this->om, static::PAGE_CLASS, $this->formFactory);
        $this->pageHandler->post($parameters);


    }

    protected function createPageHandler($objectManager, $pageClass, $formFactory)
    {
        return new PageHandler($objectManager, $pageClass, $formFactory);
    }

    protected function getPage()
    {
        $pageClass = static::PAGE_CLASS;

        return new $pageClass();
    }
}

/**
 * Class DummyPage
 *
 * @package Acme\BlogBundle\Tests\Handler
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class DummyPage extends Page
{

}