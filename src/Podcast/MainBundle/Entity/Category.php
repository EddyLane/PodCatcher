<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Podcast\MainBundle\Entity\Podcast;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\SerializerBundle\Annotation;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\CategoryRepository")
 * @UniqueEntity("name")
 * 
 * @Annotation\ExclusionPolicy("all")
 */
class Category
{
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
     * @Annotation\Expose
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var type
     * 
     * @Annotation\Expose
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Owning Side
     *
     * @ORM\ManyToMany(targetEntity="Podcast", inversedBy="categories")
     * @ORM\JoinTable(name="podcast_categories",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="podcast_id", referencedColumnName="id")}
     * )
     */
    private $podcasts;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string   $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = MyStringHelper::sluggize($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get podcats
     *
     * @return ArrayCollection
     */
    public function getPodcasts()
    {
        return $this->podcasts;
    }

    /**
     * 
     * @param \Podcast\MainBundle\Entity\Podcast $podcast
     */
    public function addPodcast(Podcast $podcast)
    {
        $this->podcasts[] = $podcast;
    }

    /**
     * Set podcasts
     *
     * @param ArrayCollection $podcasts
     */
    public function setPodcasts(ArrayCollection $podcasts)
    {
        $this->podcasts = $podcasts;
    }

    /**
     * @return type
     */
    public function __toString()
    {
        return $this->name;
    }

}
