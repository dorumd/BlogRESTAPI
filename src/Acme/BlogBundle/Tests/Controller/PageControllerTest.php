<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 8:09 PM
 */

namespace Acme\BlogBundle\Tests\Controller;


use Acme\BlogBundle\Tests\Fixtures\Entity\LoadPageData;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Acme\BlogBundle\Entity\Page;

/**
 * Class PageControllerTest
 *
 * @package Acme\BlogBundle\Tests\Controller
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class PageControllerTest extends WebTestCase
{
    /**
     * Custom set up
     * @param array $fixtures
     */
    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    /**
     * Test json get page action
     */
    public function testJsonGetPageAction()
    {
        $fixtures = ['Acme\BlogBundle\Tests\Fixtures\Entity\LoadPageData'];
        $this->customSetUp($fixtures);
        $pages = LoadPageData::$pages;
        $page = array_pop($pages);

        $route = $this->getUrl('api_1_get_page', ['id' => $page->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();

        $decoded = json_decode($content, true);

        $this->assertTrue(isset($decoded['id']));
    }

    /**
     * Test json post action
     */
    public function testJsonPostAction()
    {
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            'api/v1/pages.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "title1", "body": "body1"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostActionShouldReturn400WithBadParameters()
    {
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            'api/v1/pages.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"code": "ninja"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson = true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: ['.$response->getContent().']'
            );
        }
    }
}