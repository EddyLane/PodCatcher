<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Gherkin\Node\TableNode;

use Podcast\MainBundle\Entity\Category,
    Podcast\MainBundle\Entity\Podcast;


/**
 * Description of CategoryContext
 *
 * @author eddy
 */
class CategoryContext extends RawMinkContext {

    private $kernel;
    private $parameters;
    private $executor;
    private $context;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters) {
        $this->parameters = $parameters;
    }

    public function getContext() {
        if (!$this->context) {
            $this->context = $this->getMainContext();
        }
        return $this->context;
    }

    public function getEntityManager() {
        return $this->getContext()->getEntityManager();
    }
    
    private function getRepository($entityName) {
        return $this->getEntityManager()->getRepository(sprintf('PodcastMainBundle:%s', $entityName));
    }
    
        

    /**
     * @Given /^there are categories:$/
     */
    public function thereAreCategories(TableNode $categoryTable) {
        
        $em = $this->getEntityManager();
        foreach ($categoryTable->getHash() as $categoryRow) {
            $exists = $this->getRepository('Category')->findOneByName($categoryRow['name']);
            $category =  $exists ? $exists : new Category();
            $category->setName($categoryRow['name']);
            $em->persist($category);
            $em->flush();
            $em->clear();
        }
    }
    
    /**
     * @Given /^there are podcasts:$/
     */
    public function thereArePodcasts(TableNode $podcastTable) {
                
        foreach ($podcastTable->getHash() as $podcastRow) {
            
            $exists = $this->getRepository('Podcast')->findOneByName($podcastRow['name']);
            $podcast =  $exists ? $exists : new Podcast();
            
            $podcast->setName($podcastRow['name']);
            $podcast->setLink($podcastRow['link']);
            
            $this->getEntityManager()->persist($podcast);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }
    
    /**
     * @Then /^I should get a json response containing podcast "([^"]*)"$/
     */
    public function iShouldGetAJsonResponseContainingPodcast($podcastName)
    {
        $podcast = $this->getRepository('Podcast')->findOneByName($podcastName);
        
        $expected = array(
            array(
            "name" => $podcast->getName(),
            "slug" => $podcast->getSlug()
        ));
        sort($expected);
        
        $result = json_decode($this->getSession()->getPage()->getContent(), true);
        sort($result);
                
        if($result !== $expected) {
            throw new \Exception("Arrays are not equal");
        }
    }
    
    /**
     * @Then /^I should get a json response not containing podcast "([^"]*)"$/
     */
    public function iShouldGetAJsonResponseNotContainingPodcast($podcastName)
    {
        $podcast = $this->getRepository('Podcast')->findOneByName($podcastName);
        
        $expected = array(
            array(
            "name" => $podcast->getName(),
            "slug" => $podcast->getSlug()
        ));
        sort($expected);
        
        $result = json_decode($this->getSession()->getPage()->getContent(), true);
        sort($result);
                
        if($result === $expected) {
            throw new \Exception("Arrays are equal");
        }
    }

    
}