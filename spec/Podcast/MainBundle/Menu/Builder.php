<?php

namespace spec\Podcast\MainBundle\Menu;

use PHPSpec2\ObjectBehavior;

use Podcast\MainBundle\Entity\Category;
use Knp\Menu\MenuItem;

class Builder extends ObjectBehavior
{
    private $container;
    
    /**
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param Doctrine\ORM\EntityManager $em
     * @param Podcast\MainBundle\Repository $categoryRepository
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Knp\Menu\Silex\RouterAwareFactory $factory
     */
    function let($doctrine, $em, $categoryRepository, $container)
    {
        $em->getRepository('Podcast\MainBundle\Entity\Category')->willReturn($categoryRepository);
        $doctrine->getEntityManager()->willReturn($em);
        $this->container = $container;
        $this->setContainer($container);
    }
    
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Podcast\MainBundle\Menu\Builder');
    }
    
    /**
     * @param Knp\Menu\MenuItem $menu
     */    
    function it_should_list_all_categories($menu)
    {
        
        $entity = new Category;
        $entity->setName('Test #1');
        
        $final = $menu->addChild('Categories', array(
            'route' => 'category'
        ));
        
        $final['Categories']->addChild(array($entity->getName(), array('route' => 'category_new' )));
        
        $this->addCategories($menu, array($entity))
             ->shouldReturn($final);
        
    }
    
}
