<?php

namespace Podcast\MainBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation;


/**
 * Podcast\MainBundle\Entity\Episode
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\EpisodeRepository")
 * 
 * @Annotation\ExclusionPolicy("all")
 */
class Episode {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Annotation\Expose
     */
    private $id;

    /**
     * @var string $hash
     *
     * @ORM\Column(name="hash", type="string", unique=true)
     */
    private $hash;

    /**
     * @var string $name
     * @Annotation\Expose
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var date $date
     * @Annotation\Expose
     * @ORM\Column(name="pub_date", type="datetime", nullable=true)
     * @Assert\Null()
     */
    private $pub_date;

    /**
     * @var type
     * @Annotation\Expose
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Null()
     *  
     */
    private $description;

    /**
     *
     * @var type
     * @Annotation\Expose
     * @ORM\Column(name="length", type="integer", nullable=true)
     * @ Assert\Null()
     */
    private $length;

    /**
     * @var string $link
     * @Annotation\Expose
     * @ORM\Column(name="link", type="string", length=1020)
     */
    private $link;
    
    
    private $podcast_id;
    /**
     * @ORM\ManyToOne(targetEntity="Podcast", inversedBy="episodes")
     * @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     * @var type 
     */
    protected $podcast;
    

    /**
     *
     * @var type
     * 
     * @ORM\Column(name="expired", type="boolean") 
     */
    private $expired = false;

    /**
     * @ORM\ManyToMany(targetEntity="Playlist", mappedBy="episodes", cascade={"persist"})
     */
    private $playlists;
    

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="listenedTo", cascade={"all"})
     */
    protected $listenedBy;

    public function addPlaylist($playlist) {
        $this->playlists[] = $playlist;
    }

    public function removePlaylist(Playlist $playlist) {
        $this->playlists->removeElement($playlist);
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function setPubDate($date) {
        $this->pub_date = $date;
    }

    public function getPubDate() {
        $date = new \DateTime($this->pub_date);
        return $date->format('Y-m-d');
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setLength($length) {
        $this->length = $length;
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
     * Set link
     *
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    public function setHash($hash) {
        $this->hash = $hash;
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
     * Set podcast_id
     *
     * @param integer $podcastId
     */
    public function setPodcastId($podcastId) {
        $this->podcast_id = $podcastId;
    }

    /**
     * Get podcast_id
     *
     * @return integer 
     */
    public function getPodcastId() {
        return $this->podcast_id;
    }

    /**
     * Set user
     *
     * @param Podcast\MainBundle\Entity\Podcast $podcast
     */
    public function setPodcast(\Podcast\MainBundle\Entity\Podcast $podcast) {
        $this->podcast = $podcast;
    }

}
