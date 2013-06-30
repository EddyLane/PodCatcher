<?php

namespace Podcast\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

use Podcast\MainBundle\Entity\Podcast,
    Podcast\MainBundle\Entity\Playlist,
    Podcast\MainBundle\Entity\Episode;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\SerializerBundle\Annotation;
/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 * @Annotation\ExclusionPolicy("all")
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
     * @Annotation\Expose
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
    
    public function getListenedTo()
    {
        return $this->listenedTo;
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
}
