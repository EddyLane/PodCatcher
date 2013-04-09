<?php

// src/Podcast/MainBundle/Menu/Builder.php

namespace Podcast\MainBundle\Menu;

use Knp\Menu\FactoryInterface,
    Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {        
        $menu = $factory->createItem('root');
                
        $categories = $this->container
                           ->get('doctrine')
                           ->getManager()
                           ->getRepository('Podcast\MainBundle\Entity\Category')
                           ->findAllWithDefaultSort('name','asc');
                        
        $menu = $this->addCategories($menu, $categories);
        
        return $menu;
    }
    
    
    public function addCategories(MenuItem $menu, $categories)
    {         
        foreach($categories as $category) {
            $menu->addChild($category->getName(), array('route' => 'get_category_podcasts', 'routeParameters' => array('slug' => $category->getSlug()) ));
            $menu[$category->getName()]->setLinkAttribute('data-slug', $category->getSlug());
        }
        
        
        
        return $menu;
     }

}
