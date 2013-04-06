<?php

namespace spec;

use PHPSpec2\ObjectBehavior;
use Doctrine\ORM\Query;

class ProductManager extends ObjectBehavior
{

    /**
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param Doctrine\ORM\EntityManager $em
     * @param Havells\ProductFinderBundle\Repository\ProductRepository $repository
     * @param Doctrine\ORM\Query $query
     */
    function let($doctrine, $em, $repository, $query)
    {
        //you'll need to call this in your implementation to get the entity manager
        //so setting up the mock here
        $doctrine
            ->getEntityManager()
            ->willReturn($em)
        ;

        //you'll need to call this in your implementation to get the repository for your given entity
        //so setting up the mock here
        $em
            ->getRepository('ProductClass')
            ->willReturn($repository)
        ;

        //class should be constructed with doctrine and the entity name
        $this->beConstructedWith($doctrine, 'ProductClass');

    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('ProductManager');
    }


    function it_should_find_an_array_of_products_by_category_id($repository, $query)
    {
        //this is our input param
        $categoryId = 1;

        //this is our output data
        $productData = array(array('id' => 1, 'name' => 'Test'));

        //use the entities repo to make the query
        $repository
            ->findByCategory($categoryId)
            ->shouldBeCalled()
            ->willReturn($query)
        ;

        //ensure the query is being hydrated as an array
        $query
            ->getResult(Query::HYDRATE_ARRAY)
            ->shouldBeCalled()
            ->willReturn($productData)
        ;

        //make the call
        $this
            ->findByCategory($categoryId)
            ->shouldReturn($productData)
        ;
    }

}
