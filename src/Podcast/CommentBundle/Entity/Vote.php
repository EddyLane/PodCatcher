<?php
// src/MyProject/MyBundle/Entity/Vote.php

namespace Podcast\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Vote as BaseVote;
use FOS\CommentBundle\Model\SignedVoteInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Vote extends BaseVote implements SignedVoteInterface
{


    public static $values = array(
        -1, 0, 1
    );

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Comment of this vote
     *
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Podcast\CommentBundle\Entity\Comment")
     */
    protected $comment;

 // .. fields

    /**
     * Author of the vote
     *
     * @ORM\ManyToOne(targetEntity="Podcast\UserBundle\Entity\User")
     * @var User
     */
    protected $voter;


    public function setValue($value) 
    {
        if(!in_array($value,self::$values)) {
            throw new \Exception('Value must be either -1, 0 or 1');
        }
        $this->value = $value;
    }
    /**
     * Sets the owner of the vote
     *
     * @param string $user
     */
    public function setVoter(UserInterface $voter)
    {
        $this->voter = $voter;
    }

    /**
     * Gets the owner of the vote
     *
     * @return UserInterface
     */
    public function getVoter()
    {
        return $this->voter;
    }
}
