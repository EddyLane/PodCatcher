<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;

use Podcast\MainBundle\Entity\Category,
    Podcast\MainBundle\Entity\Podcast;


/**
 * Description of CategoryContext
 *
 * @author eddy
 */
class CategoryContext extends BehatContext {

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
        return $this->getEntityManager()->getRepository(sprintf('Podcast\MainBundle\Entity\%s', $entityName));
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
        }
    }
    
    /**
     * @Given /^there are podcasts:$/
     */
    public function thereArePodcasts(TableNode $podcastTable) {
        
        $em = $this->getEntityManager();
        foreach ($podcastTable->getHash() as $podcastRow) {
            $exists = $this->getRepository('Podcast')->findOneByName($podcastRow['name']);
            $podcast =  $exists ? $exists : new Podcast();
            $podcast->setName($podcastRow['name']);
            $podcast->setLink($podcastRow['link']);
            $podcast->addCategory($this->getRepository('Category')->findOneByName($podcastRow['category']));
            $em->persist($podcast);
            $em->flush();
        }
    }
}