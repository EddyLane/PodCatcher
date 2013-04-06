<?php

namespace Podcast\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Podcast\MainBundle\Entity\Podcast,
    Podcast\MainBundle\Entity\Playlist,
    Podcast\MainBundle\Entity\Episode;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToOne(targetEntity="Podcast\MainBundle\Entity\Playlist", inversedBy="user")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var type
     */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="Podcast\MainBundle\Entity\Podcast", inversedBy="subscribed", cascade={"persist"})
     * @ORM\JoinTable(name="user_podcast",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="podcast_id", referencedColumnName="id")}
     * )
     */
    private $subscriptions;

    /**
     * @ORM\ManyToMany(targetEntity="Podcast\MainBundle\Entity\Episode", inversedBy="listenedBy", cascade={"all"})
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

    public function removeListenedTo(Episode $episode)
    {
        $this->listenedTo->removeElement($episode);
    }

    public function addListenedTo(Episode $episode)
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
    public function addSubscription(Podcast $podcast)
    {
        if (!$this->subscriptions->contains($podcast)) {
            $this->subscriptions->add($podcast);
        }
    }

    public function removeSubscription(Podcast $podcast)
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
    public function addPlaylist(Playlist $playlists)
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
