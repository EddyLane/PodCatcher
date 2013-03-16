<?php

namespace Podcast\MainBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToOne(targetEntity="Playlist", inversedBy="user")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var type
     */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="Podcast", inversedBy="subscribed", cascade={"persist"})
     * @ORM\JoinTable(name="user_podcast",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="podcast_id", referencedColumnName="id")}
     * )
     */
    private $subscriptions;

    /**
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="listenedBy", cascade={"all"})
     * @ORM\JoinTable(name="user_episode",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="episode_id", referencedColumnName="id")}
     * )
     */
    private $listenedTo;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function removeListenedTo(\Podcast\MainBundle\Entity\Episode $episode)
    {
        $this->listenedTo->removeElement($episode);
    }

    public function addListenedTo(\Podcast\MainBundle\Entity\Episode $episode)
    {
        if (!$this->listenedTo->contains($episode)) {
            $this->listenedTo[] = $episode;
        }
    }

    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    public function getPublicData()
    {
        return array(
            "username" => $this->getUsername(),
            "email" => $this->getEmail(),
            "subscriptions" => $this->subscriptions
        );
    }

    /**
     * Add playlists
     *
     * @param Podcast\MainBundle\Entity\Playlist $playlists
     */
    public function addSubscription(\Podcast\MainBundle\Entity\Podcast $podcast)
    {
        if (!$this->subscriptions->contains($podcast)) {
            $this->subscriptions->add($podcast);
        }
    }

    public function removeSubscription(\Podcast\MainBundle\Entity\Podcast $podcast)
    {
        if ($this->subscriptions->contains($podcast)) {
            $this->subscriptions->removeElement($podcast);
        }
    }

    /**
     * Get playlists
     *
     * @return Doctrine\Common\Collections\Collection
     */
//    public function getSubscriptions() {
//        return $this->subscriptions;
//    }

    /**
     * Add playlists
     *
     * @param Podcast\MainBundle\Entity\Playlist $playlists
     */
    public function addPlaylist(\Podcast\MainBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;
    }

    /**
     * Get playlists
     *
     * @return Doctrine\Common\Collections\Collection
     */
//    public function getPlaylists() {
//        return $this->playlists;
//    }
}
