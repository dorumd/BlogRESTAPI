<?php
/**
 * Created by PhpStorm.
 * User: dorelmardari
 * Date: 4/18/15
 * Time: 9:26 PM
 */

namespace Acme\BlogBundle\Exception;

/**
 * Class InvalidFormException
 *
 * @package Acme\BlogBundle\Exception
 * @author  Mardari Dorel <mardari.dorua@gmail.com>
 */
class InvalidFormException extends \RuntimeException
{
    protected $form;

    /**
     * @param string $message
     * @param null   $form
     */
    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}