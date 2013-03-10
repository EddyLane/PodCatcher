<?php

// src/Podcast/MainBundle/Menu/Builder.php

namespace Podcast\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {

    public function mainMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');


        $menu->addChild('Categories', array(
            'route' => 'category',
        ));

        // ... add more children

        return $menu;
    }

}