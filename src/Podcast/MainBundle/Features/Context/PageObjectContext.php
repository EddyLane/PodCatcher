<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edwardlane
 * Date: 09/07/2013
 * Time: 23:07
 * To change this template use File | Settings | File Templates.
 */

namespace Podcast\MainBundle\Features\Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext as BasePageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Context\Step;
use Behat\Mink\Exception\ElementNotFoundException;

class PageObjectContext extends BasePageObjectContext
{
    /**
     * @Given /^(?:|I )visited (?:|the )(?P<pageName>.*?)$/
     */
    public function iVisitedThePage($pageName)
    {
        $this->getPage($pageName)->open();
    }
    /**
     * @When /^I submit the login form with the following details:$/
     */
    public function iSubmitTheLoginFormWithTheFollowingDetails(TableNode $table)
    {
        $page = $this->getPage('Homepage')->open();
        $page->openLoginForm();
        $page->fillLoginForm($table->getHash());
        $page->submitLoginForm();
    }

}