<?php

namespace CoreSys\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use CoreSys\NotesBundle\Entity\Note;

/**
 * User
 *
 * This is an extension entity of the FOSUserBundle
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CoreSys\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=32, nullable=true)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=32, nullable=true)
     */
    private $last_name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreSys\NotesBundle\Entity\Note")
     * @ORM\OrderBy({"created_at"="DESC"})
     */
    private $notes;

    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt( new \DateTime() );
        $this->setUpdatedAt( new \DateTime() );
        $this->setNotes( new ArrayCollection() );
        $this->setLastLogin( new \DateTime() );
    }

    /**
     * Get created_at
     *
     * @param string $format
     *
     * @return \DateTime
     */
    public function getCreatedAt( $format = NULL )
    {
        if ( !empty( $format ) ) {
            return $this->created_at->format( $format );
        }

        return $this->created_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt( $createdAt )
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @param string $format
     *
     * @return \DateTime
     */
    public function getUpdatedAt( $format = NULL )
    {
        if ( !empty( $format ) ) {
            return $this->updated_at->format( $format );
        }

        return $this->updated_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt( $updatedAt )
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName( $firstName )
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName( $lastName )
    {
        $this->last_name = $lastName;

        return $this;
    }

    public function setActive( $active = TRUE )
    {
        $this->setEnabled( $active );

        return $this;
    }

    public function getActive()
    {
        // first off, active really means that this user is both enabled
        // and has a non expired subscription
        $enabled = $this->enabled === TRUE;
        $active  = $this->getActiveSubscription();
        if ( empty( $active ) ) {
            return FALSE;
        }

        $expired = $active->getExpired();
        if ( $expired ) {
            return FALSE;
        }

        // active subscription
        return $enabled;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes( $notes )
    {
        if ( empty( $notes ) ) {
            $notes = new ArrayCollection();
        }
        $this->notes = $notes;

        return $this;
    }

    public function addNote( Note $note )
    {
        if ( !$this->notes->contains( $note ) ) {
            $this->notes->add( $note );
        }

        return $this;
    }

    public function removeNote( Note $note )
    {
        if ( $this->notes->contains( $note ) ) {
            $this->notes->removeElement( $note );
        }

        return $this;
    }

    public function getLastLogin( $format = NULL )
    {
        $last = $this->lastLogin;
        if ( empty( $last ) ) {
            $last = new \DateTime();
            $time = mktime( 0, 0, 0, 1, 1, 2000 );
            $last->setTimestamp( $time );
//            return !empty( $format ) ? 'No Data' : null;
        }

        if ( !empty( $format ) ) {
            return $last->format( $format );
        }

        return $last;
    }

    public function getRole( $format = FALSE )
    {
        $roles        = array(
            'ROLE_USER', 'ROLE_MEMBER', 'ROLE_MODERATOR', 'ROLE_MODEL', 'ROLE_PHOTOGRAPHER', 'ROLE_VIDEOGRAPHER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_OWNER'
        );
        $return       = NULL;
        $return_index = 0;
        foreach ( $this->getRoles() as $role ) {
            $index = array_search( $role, $roles );
            if ( empty( $return ) ) {
                $return       = $role;
                $return_index = $index;
            }
            else {
                if ( $return_index < $index ) {
                    $return       = $role;
                    $return_index = $index;
                }
            }
        }

        if ( $format ) {
            $role = strtolower( $return );
            $role = str_replace( 'role_', '', $role );
            $role = str_replace( '_', ' ', $role );
            $role = ucwords( $role );

            return $role;
        }

        return $return;
    }
}



