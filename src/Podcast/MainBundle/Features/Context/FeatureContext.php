<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\MinkExtension\Context\MinkContext,
    Behat\MinkExtension\Context\MinkAwareInterface,
    Behat\Symfony2Extension\Context\KernelAwareInterface,
    Behat\Mink\Mink;

use Symfony\Component\HttpKernel\KernelInterface;

use Podcast\MainBundle\Features\Context\CategoryContext;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext implements MinkAwareInterface, KernelAwareInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel;
    private $minkParameters;
    private $mink;

    public function __construct()
    {
        $this->useContext('mink', new MinkContext());
        $this->useContext('category', new CategoryContext());
    }
    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     *
     * @return null
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * @Given /There is no "([^"]*)" in database/
     */
    public function thereIsNoRecordInDatabase($entityName)
    {
        $entities = $this->getEntityManager()->getRepository('PodcastMainBundle:' . $entityName)->findAll();
        foreach ($entities as $eachEntity) {
            $this->getEntityManager()->remove($eachEntity);
        }

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @Given /I have a category "([^"]*)"/
     */
    public function iHaveACategory($name)
    {
        $category = new \Podcast\MainBundle\Entity\Category();
        $category->setName($name);
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @Given /I have a podcast "([^"]*)"/
     */
    public function iHaveAPodcast($name)
    {
        $podcast = new \Podcast\MainBundle\Entity\Podcast();
        $podcast->setName($name);
        $podcast->setLink("http://" + $this->generateRandomString(30));
        $this->getEntityManager()->persist($podcast);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @Given /^There is a podcast$/
     */
    public function thereIsAPodcast()
    {
        $podcast = new \Podcast\MainBundle\Entity\Podcast();
        $podcast->setName($this->generateRandomString(10));
        $podcast->setLink("http://" + $this->generateRandomString(30));
        $this->getEntityManager()->persist($podcast);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @Then /^I should find podcast "([^"]*)" in category "([^"]*)"$/
     */
    public function iShouldFindPodcastInCategory($podcastName, $categoryName)
    {
        $category = $this->getEntityManager()->getRepository('PodcastMainBundle:Category')->findOneByName($categoryName);

        $found = false;

        foreach ($category->getPodcasts() as $podcast) {
            if ($podcastName === $podcast->getName()) {
                $found = true;
                break;
            }
        }

        assertTrue($found);
    }

    /**
     * @When /^I add podcast "([^"]*)" to category "([^"]*)"$/
     */
    public function iAddPodcastToCategory($podcastName, $categoryName)
    {
        $podcast = $this->getEntityManager()->getRepository('PodcastMainBundle:Podcast')->findOneByName($podcastName);
        $category = $this->getEntityManager()->getRepository('PodcastMainBundle:Category')->findOneByName($categoryName);

        $category->addPodcast($podcast);

        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @Then /^That podcast should be displayed$/
     */
    public function thatPodcastShouldBeDisplayed()
    {
        throw new \Exception("Oh you fucked");
        throw new PendingException();
    }

    /**
     * Generate a random string of a given length
     *
     * @param  int    $length
     * @return string
     */
    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    protected function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine')->getEntityManager();
    }

}
