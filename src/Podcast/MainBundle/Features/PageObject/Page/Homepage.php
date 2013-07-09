<?php

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Homepage extends Page
{
    /**
     * @var string $path
     */
    protected $path = '/';
    protected $elements = array(
        'Search form' => array('css' => 'form#search'),
        'Login form' => array('css' => 'form#login-form'),
        'Registration form' => array('css' => 'form#registration-login'),
        'Navigation' => array('css' => '.header div.navigation')
    );


}