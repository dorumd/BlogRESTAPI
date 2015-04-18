<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 8:05 PM
 */

namespace Acme\BlogBundle\Tests\Fixtures\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Entity\Page;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * Class LoadPageData
 *
 * @package Acme\BlogBundle\Tests\Fixtures\Entity
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class LoadPageData implements FixtureInterface
{
    static public $pages = [];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $page = new Page();
        $page->setTitle('title');
        $page->setBody('body');

        $manager->persist($page);
        $manager->flush();

        self::$pages[] = $page;
    }
}