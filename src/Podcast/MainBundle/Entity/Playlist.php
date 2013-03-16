<?php

namespace Podcast\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Podcast\MainBundle\Entity\Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Podcast\MainBundle\Entity\PlaylistRepository")
 */
class Playlist
{
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
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\Null()
     */
    private $name = null;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug = null;

    /**
     * @var integer $user_id
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;

    /**
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating = 1;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="playlists")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="playlists", cascade={"all"})
     * @ORM\JoinTable(name="playlist_episode",
     * joinColumns={@ORM\JoinColumn(name="playlist_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="episode_id", referencedColumnName="id")}
     * )
     */
    private $episodes;

    public function addEpisode(Episode $episode)
    {
        $episode->addPlaylist($this);
        $this->episodes[] = $episode;
    }

    public function removeEpisode(Episode $episode)
    {
        $episode->removePlaylist($this);
        $this->episodes->removeElement($episode);

    }

    public function getEpisodes()
    {
        return $this->episodes;
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = MyStringHelper::sluggize($name);
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
     * Set user_id
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Set user
     *
     * @param Podcast\MainBundle\Entity\User $user
     */
    public function setUser(\Podcast\MainBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Podcast\MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
