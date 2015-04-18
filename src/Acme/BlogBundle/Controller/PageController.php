<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 7:11 PM
 */

namespace Acme\BlogBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Form\PageType;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PostController
 *
 * @package Acme\BlogBundle\Controller
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class PageController extends FOSRestController
{
    /**
     * List all pages.
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returns when successful"
     *      }
     * )
     *
     * @Annotations\QueryParam(
     *      name="offset",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Offset from which to start listing pages"
     * )
     *
     * @Annotations\QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      default="5",
     *      description="How many pages to return"
     * )
     *
     * @Annotations\View(
     *      templateVar="pages"
     * )
     *
     * @param Request      $request
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getPagesAction(Request $request, ParamFetcher $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;

        $limit = $paramFetcher->get('limit');

        return $this->container->get('acme_blog.page.handler')->all($limit, $offset);
    }

    /**
     * Get a single page
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Page for given id",
     *   output = "Acme\BlogBundle\Entity\Page",
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the page id was not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="page")
     *
     * @param int $id the page id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getPageAction($id)
    {
        $page = $this->getOr404($id);

        return $page;
    }

    /**
     * Presents the form to use to create a new page
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return \Symfony\Component\Form\Form
     */
    public function newPageAction()
    {
        return $this->createForm(new PageType());
    }

    /**
     * Create a page from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new page from the submitted data",
     *   input = "Acme\BlogBundle\Form\PageType",
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "AcmeBlogBundle:Page:newPage.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @return array|\FOS\RestBundle\View\View
     */
    public function postPageAction()
    {
        try {
            $newPage = $this->container->get('acme_blog.page.handler')->post(
                $this->container->get('request')->request->all()
            );

            $routeOptions = [
                'id' => $newPage->getId(),
                '_format' => $this->container->get('request')->get('_format')
            ];

            return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return ['form' => $exception->getForm()];
        }
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @ApiDoc(
     *      resource = true,
     *      input = "Acme\DemoBundle\Form\PageType",
     *      description = "Updates a page",
     *      statusCodes = {
     *          200 = "Returned when the page is created",
     *          204 = "Returned when successful",
     *          400 = "Returned when the form has errors"
     *      }
     * )
     *
     * @Annotations\View(
     *      template="AcmeBlogBundle:Page:editPage.html.twig",
     *      templateVar="form"
     * )
     *
     * @return array|\FOS\RestBundle\View\View|null
     *
     * @throws NotFoundHttpException when page not exists
     */
    public function putPageAction(Request $request, $id)
    {
        try {
            if (!($page = $this->container->get('acme_blog.page.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $page = $this->container->get('acme_blog.page.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $page = $this->container->get('acme_blog.page.handler')->put(
                    $page,
                    $request->request->all()
                );
            }

            $routeOptions = [
                'id' => $page->getId(),
                '_format' => $request->get('_format'),
            ];

            return $this->routeRedirectView('api_1_get_page', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing page from the submitted data
     *
     * @ApiDoc(
     *      resource = true,
     *      input = "Acme\DemoBundle\Form\PageType",
     *      statusCodes = {
     *          204 = "Returned when successful",
     *          400 = "Returned when the form has errors"
     *      }
     * )
     *
     * @Annotations\View(
     *      template = "AcmeDemoBundle:Page:editPage.html.twig",
     *      templateVar = "form"
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array|\FOS\RestBundle\View\View|null
     */
    public function patchPageAction(Request $request, $id)
    {
        try {
            $page = $this->container->get('acme_blog.page.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = [
                'id' => $page->getId(),
                '_format' => $request->get('_format')
            ];

            return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Fetch a page or throw an 404 exception
     *
     * @param mixed $id
     *
     * @return \Acme\BlogBundle\Model\PageInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($page = $this->container->get('acme_blog.page.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $page;
    }
}