<?php

namespace Podcast\MainBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\BehatContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\CommonContexts\WebApiContext;
use Behat\Behat\Context\Step;

/**
 * Feature context.
 */
class FeatureContext extends BaseContext //MinkContext if you want to test web
{
    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->useContext('pageObject', new PageObjectContext($parameters));
        $this->useContext('database', new DatabaseContext($parameters));
    }


   /**
    * @Given /^I am not authenticated$/
    */
    public function iAmNotAuthenticated()
    {
    }

}