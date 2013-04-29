<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Podcast\MainBundle\Entity\Episode;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\SerializerBundle\Annotation;

/**
 * Podcast\MainBundle\Entity\Podcast
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\PodcastRepository")
 *
 * @Annotation\ExclusionPolicy("all")
 *
 */
class Podcast
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Annotation\Expose
     * @var string $name
     * @ORM\Column(name="name", type="string", length=1020)
     */
    private $name;

    /**
     * @Annotation\Expose
     * @var type
     * @ORM\Column(name="slug", type="string", length=1020)
     */
    private $slug;

    /**
     * @var string $link
     * @ORM\Column(name="link", type="string", length=1020)
     */
    private $link;

    /**
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating = 1;

    /**
     * @var string $image
     * 
     * @Annotation\Expose
     * @ORM\Column(name="image", type="string", length=1020, nullable=true)
     * @Assert\Null()
     */
    private $image;

    /**
     * @Annotation\Expose
     * @var string $description
     * @ORM\Column(name="description", type="string", length=1020, nullable=true)
     * @Assert\Null()
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="podcast", cascade={"all"})
     * @ORM\OrderBy({"pub_date" = "DESC"})
     *
     * @var ArrayCollection $episodes
     */
    protected $episodes;

    /**
     * @ORM\ManyToMany(targetEntity="Podcast\UserBundle\Entity\User", mappedBy="subscriptions", cascade={"persist"})
     */
    private $subscribed;

    /**
     * @Annotation\Expose
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="podcasts")
     * @ORM\OrderBy({"name" = "DESC"})
     */
    private $categories;
    
    /**
     * @Annotation\Expose
     * @ORM\ManyToMany(targetEntity="Organization", mappedBy="podcasts")
     * @ORM\OrderBy({"name" = "DESC"})
     */
    private $organizations;
    /**
     * The feed
     * @var type
     */
    private $xml;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->organizations = new ArrayCollection();
    }

    /**
     * Get name
     *
     * @return type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param type $name
     */
    public function setName($name)
    {
        if ($this->slug === null) {
            $this->slug = MyStringHelper::sluggize($name);
        }
        $this->name = $name;
    }

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
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Clean it up a bit
     *
     * @param type $feed
     */
    public function cleanEpisodes($feed)
    {
        $feed->set_feed_url($this->getLink());
        $feed->init();
        $items = $feed->get_items();
        foreach ($this->episodes as $key => $episode) {
            $found = false;
            foreach ($items as $key => $item) {
                if ($episode->getHash() === $item->get_id()) {
                    //echo "Episode alive: ".$episode->getName()."\n";
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                //echo "Found dead episode: ".$episode->getName()."\n";
                $this->episodes->remove($key);
            }
        }
    }

 
    public function clearEpisodes()
    {
        $this->episodes->clear();
    }
    /**
     *
     * @param type $description
     */
    public function setDescription($description)
    {
        if ((string) strlen($description) > 0) {
            $this->description = $description;
        }
    }

    public function setImage($image)
    {
        if ((string) strlen($image) > 0) {
            $this->image = $image;
        }
    }

    /**
     * @param  string $image
     * @return image
     */
    public function getImage()
    {
        if (null === $this->image) {
            return "http://placehold.it/266/ffffff/&text=" . $this->getName();
        }

        return $this->image;
    }

    /**
            * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     *
     * @return type
     */
    public function __toString()
    {
        return $this->name;
    }
    
    public function getEpisodes()
    {
        return $this->episodes;
    }
    
    public function addEpisode($episode)
    {
        $this->episodes->add($episode);
    }
    
    public function addCategory($category)
    {
        $this->categories->add($category);
    }
    
    public function addOrganization($organization)
    {
        $this->organizations->add($organization);
    }    
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
    
    public function setOrganizations($organizations)
    {
        $this->organizations = $organizations;
    }

}
