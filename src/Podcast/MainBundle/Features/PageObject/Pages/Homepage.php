<?php

namespace Podcast\MainBundle\Features\PageObject\Pages;

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
        'Navigation' => array('css' => '.navbar-fixed-top')
    );

    public function openLoginForm()
    {
        $this->find('css', '#login-ctrl')->click();
    }

    public function submitLoginForm()
    {
        $this->find('css','form#login-form')->find('css', 'button')->press();
    }

    public function fillLoginForm($fields)
    {
        $form = $this->getElement('Login form');
        foreach($fields as $field) {
            $form->fillField('username', $field['username']);
            $form->fillField('password', $field['password']);
        }
    }

}