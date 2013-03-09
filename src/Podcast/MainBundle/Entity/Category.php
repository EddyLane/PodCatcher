<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\CategoryRepository")
 */
class Category {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Owning Side
     *
     * @ORM\ManyToMany(targetEntity="Podcast", inversedBy="categories")
     * @ORM\JoinTable(name="podcast_categories",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="podcast_id", referencedColumnName="id")}
     *      )
     */
    private $podcasts;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }
    
    
    /**
     * Get podcats
     * 
     * @return ArrayCollection
     */
    public function getPodcasts() {
        return $this->podcasts;
    }
    
    /**
     * Set podcasts
     * 
     * @param \Podcast\MainBundle\Entity\ArrayCollection $podcasts
     */
    public function setPodcasts($podcasts) {
        $this->podcasts = $podcasts;
    }

}
