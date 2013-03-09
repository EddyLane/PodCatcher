<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use SimpleXMLElement;
use Podcast\MainBundle\Entity\Episode;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * Podcast\MainBundle\Entity\Podcast
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\PodcastRepository")
 */
class Podcast {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=1020)
     */
    private $name;

    /**
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
     * @ORM\Column(name="image", type="string", length=1020, nullable=true)
     * @Assert\Null()
     */
    private $image;
    
    /**
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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="subscriptions", cascade={"persist"})
     */
    private $subscribed;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="podcasts")
     */
    private $categories;
    
    
    private $xml;
    
    
    
    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    /**
     * Get name
     * 
     * @return type 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     * 
     * @param type $name 
     */
    public function setName($name) {

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating() {
        return $this->rating;
    }

    public function getEpisodes($amount = 5) {

        $episodes = $this->episodes->toArray();

    }

    private function getPodcastFiles($amount = 100) {

        $this->getFeed();
        $podcasts = array();

        for ($i = 0; $i < $amount; $i++) {
            if (isset($this->xml->channel->item[$i])) {
                $item = $this->xml->channel->item[$i];  
                $podcasts[(string) $item->enclosure["url"]] = array(
                    "title" => (string) $item->title,
                    "description" => (string) $item->description,
                    "pub_date" => (string) $item->pubDate,
                    "length" => (string) $item->enclosure["length"],
                    "type" => (string) $item->enclosure["type"],
                    "guid" => (string) $item->enclosure["guid"]
                );
            }
        }
        return $podcasts;
    }

    
    public function cleanEpisodes($feed) {
        
        $feed->set_feed_url($this->getLink());
        $feed->init();
        $items = $feed->get_items();
        foreach($this->episodes as $key => $episode) {
            $found = false;
            foreach($items as $key => $item) {
                if($episode->getHash() === $item->get_id()) {
                    echo "Episode alive: ".$episode->getName()."\n";
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                echo "Found dead episode: ".$episode->getName()."\n";
                //unset($this->episodes[$key]);
                $this->episodes->remove($key);
            }

        }
    }
    
    public function refreshEpisodes($feed) {

        $feed->set_feed_url($this->getLink());
        $feed->init();
        //$episodes = new ArrayCollection();
        
        echo "Podcast '".$this->getName()."'\n";
        echo "--------------------------------\n";
        foreach($feed->get_items() as $key=>$item)
        {
            
            $episodeEntity = false;
           foreach($this->episodes as $episode) {
               if($episode->getHash() === $item->get_id()) {
                   $episodeEntity = $episode;
                   echo "Found episode: ".$episode->getName()."\n";
               }
           }
           if($episodeEntity === false) {
                echo "New episode: ".$item->get_title()."\n";

               $episodeEntity = new Episode();
           }

            $episodeEntity->setLink($item->get_link());
            $episodeEntity->setHash($item->get_id());
            $episodeEntity->setPubDate(new \DateTime(date('Y-m-d H:i:s',strtotime($item->get_date()))));
            $episodeEntity->setDescription($item->get_description());
            $episodeEntity->setName($item->get_title());
            $episodeEntity->setPodcast($this);
            
            if($episodeEntity) {
                $this->episodes->add($episodeEntity);
            }

        }
        echo "-----------------------------------------\n\n";
    }

    public function init() {

        $this->getFeed();
        try {
            
            $this->setImage((string) $this->xml->channel->image->url);
            $this->setName((string) $this->xml->channel->title);
            $this->setDescription((string) $this->xml->channel->description);
            
        } catch (Exception $e) {
            
            return $e->getMessage();
            
        }
        return true;
    }

    public function clearEpisodes() {
        $this->episodes->clear();
    }



    private function getFeed() {

        libxml_use_internal_errors(true);
        try {
            $xml = simplexml_load_string(file_get_contents($this->getLink()));
            if (!$xml) {
                echo "Failed loading XML\n";
                foreach (libxml_get_errors() as $error) {
                    echo "\t", $error->message;
                }
            }
            $this->xml = $xml;
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    public function setDescription($description) {
        if((string) strlen($description) > 0) {
            $this->description = $description;
        }
    }

    public function setImage($image) {
        if ((string) strlen($image) > 0) {
            $this->image = $image;
        }
    }

    /**
     * @param string $image
     * @return image
     */
    public function getImage() {

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
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }
    
    
    /**
     * 
     * @return type
     */
    public function __toString() {
        return $this->name;
    }

}