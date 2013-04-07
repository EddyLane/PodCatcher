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

        $menu->addChild('Categories', array(
            'route' => 'category',
        ));
        
        foreach($categories as $category) {
            $menu['Categories']->addChild($category->getName(), array('route' => 'category_new' ));
        }
        
        
        return $menu;
     }

}
