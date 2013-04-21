<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\SerializerBundle\Annotation;

/**
 * Organization
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\OrganizationRepository")
 * 
 * @UniqueEntity("name")
 * @Annotation\ExclusionPolicy("all")
 */
class Organization
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
     * @var string
     * 
     * @Annotation\Expose
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Owning Side
     *
     * @ORM\ManyToMany(targetEntity="Podcast", inversedBy="organizations")
     * @ORM\JoinTable(name="podcast_organizations",
     *      joinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")},
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
     * @param string $name
     * @return Organization
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

    /**
     * Set slug
     *
     * @param string $slug
     * @return Organization
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set podcasts
     *
     * @param array $podcasts
     * @return Organization
     */
    public function setPodcasts($podcasts)
    {
        $this->podcasts = $podcasts;
    
        return $this;
    }

    /**
     * Get podcasts
     *
     * @return array 
     */
    public function getPodcasts()
    {
        return $this->podcasts;
    }
}
