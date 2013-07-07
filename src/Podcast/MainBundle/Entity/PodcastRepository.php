<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Podcast\MainBundle\Entity\Category;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
/**
 * PodcastRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PodcastRepository extends EntityRepository
{

    /**
     * @param  string $sortField
     * @param  string $sortDirection
     * @return type
     */
    public function findAllWithDefaultSort($sortField, $sortDirection)
    {
        return $this->findBy(array(), array($sortField => $sortDirection));
    }
    
    
    
    /**
     * @param string $category
     * @param string $category
     */
    public function findAllByCategoryAndOrganization(
            TokenInterface $token,
            array $categories = array(), 
            array $organizations = array(), 
            $sort = null, 
            $order = 'desc', 
            $amount = 8, 
            $page = 1, 
            $search = null,
            $hydration = Query::HYDRATE_ARRAY
            )
    {
        $qb = $this->createQueryBuilder('podcast');
        
        $qb
           ->distinct()
        ;
        
        if($search) {
            $qb->add('where', $qb->expr()->like('podcast.name', ':search'))
               ->setParameter('search', $search."%");
        }

        if(count($categories) > 0) {
            $qb
               ->innerJoin('podcast.categories', 'category')
               ->add('where', $qb->expr()->in('category.slug', $categories))
            ;
        }
        if(count($organizations) > 0) {
            $qb
               ->innerJoin('podcast.organizations', 'organization')
               ->add('where', $qb->expr()->in('organization.slug', $organizations))
            ;
        }

        $metadata = array(
            'X-Pagination-Total' => count(new Paginator($qb->getQuery(), false)),
            'X-Pagination-Amount'=> $amount,
            'X-Pagination-Page' => $page
        );
        
        $qb
           ->leftJoin('podcast.episodes','episode')
           ->addSelect(sprintf('%s as HIDDEN podcast_updated', $qb->expr()->max('episode.pub_date')))
           ->groupBy('podcast.id')
           ->setMaxResults($amount)
           ->setFirstResult(($page-1) * $amount)
        ;

        if(!$sort) {
           $sort = 'podcast_updated';
        }

        $qb->orderBy($sort, $order);
        $entities = $qb
                ->getQuery()
                ->getResult($hydration);

        return array(
            'metadata' => $metadata,
            'entities' => $entities
        );
    }
    
    
    /**
     * 
     * @param \Podcast\MainBundle\Entity\Category $category
     * @return type
     */
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('p')
                    ->innerJoin('p.categories','c','WITH','c.id = :category_id')
                    ->setParameter('category_id', $category->getId());
    }

}
