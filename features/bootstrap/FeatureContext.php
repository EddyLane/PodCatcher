<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\Symfony2Extension\Context\BehatContext,
    Behat\Symfony2Extension\Context\MinkContext;
use Behat\Symfony2Extension\Context\ClosuredContextInterface,
    Behat\Symfony2Extension\Context\TranslatedContextInterface,
    Behat\Symfony2Extension\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }
    
    /**
     * @Given /^there are more than (\d+) podcast$/
     */
    public function thereAreMoreThanPodcast($arg1)
    {
        for($i = 0; $i < $arg1; $i++) {
            $podcast = new \Podcast\MainBundle\Entity\Podcast();
            $podcast->setName($name);

            $this->getEntityManager()->persist($podcast);
            $this->getEntityManager()->flush();            
        }
    }
    
    /**
     * @Then /^a grid of podcast thumbnails should be displayed$/
     */
    public function aGridOfPodcastThumbnailsShouldBeDisplayed()
    {
        throw new PendingException();
    }

    
    /**
     * Returns the Doctrine entity manager.
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
    
    /**
     * Returns a random alphanumeric string of a given length
     * 
     * @param type $length
     * @return string
     */
    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//
}
