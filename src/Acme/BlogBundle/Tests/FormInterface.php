<?php
/**
 * interface in order to mock form interface waiting https://github.com/sebastianbergmann/phpunit-mock-objects/issues/103
 */
namespace  Acme\BlogBundle\Tests;

/**
 * Interface FormInterface
 *
 * @package Acme\BlogBundle\Tests
 */
interface FormInterface extends \Iterator, \Symfony\Component\Form\FormInterface
{
}