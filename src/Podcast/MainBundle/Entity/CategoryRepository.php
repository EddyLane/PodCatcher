<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    /**
     *
     * @param  string $sortField
     * @param  string $sortDirection
     * @return type
     */
    public function findAllWithDefaultSort($sortField, $sortDirection)
    {
        return $this->findBy(array(), array($sortField => $sortDirection));
    }

}
