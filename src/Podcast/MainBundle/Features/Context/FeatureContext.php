<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkAwareInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\Mink\Mink;

use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends BehatContext implements MinkAwareInterface, KernelAwareInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel;
    private $minkParameters;
    private $mink;


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
     * @Given /^There is a podcast$/
     */
    public function thereIsAPodcast()
    {
        $podcast = new \Podcast\MainBundle\Entity\Podcast();
        $podcast->setName($this->generateRandomString(10));
        $podcast->setLink("http://"+$this->generateRandomString(30));
        $this->getEntityManager()->persist($podcast);
        $this->getEntityManager()->flush();
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
     * @param int $length
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