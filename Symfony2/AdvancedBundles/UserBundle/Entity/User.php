<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use CoreSys\NotesBundle\Entity\Note;

/**
 * User
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

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_roles")
     */
    private $sys_roles;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt( new \DateTime() );
        $this->setUpdatedAt( new \DateTime() );
        $this->setNotes( new ArrayCollection() );
        $this->setLastLogin( new \DateTime() );
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addSysRole( Role $role = NULL )
    {
        if ( !$this->sys_roles->contains( $role ) ) {
            $this->sys_roles->add( $role );
        }

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function removeSysRole( Role $role = NULL )
    {
        if ( $this->sys_roles->contains( $role ) ) {
            $this->sys_roles->removeElement( $role );
        }

        return $this;
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

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive( $active = TRUE )
    {
        $this->setEnabled( $active );

        return $this;
    }

    /**
     * @return bool
     */
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

    /**
     * @return ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param $notes
     *
     * @return $this
     */
    public function setNotes( $notes )
    {
        if ( empty( $notes ) ) {
            $notes = new ArrayCollection();
        }
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param Note $note
     *
     * @return $this
     */
    public function addNote( Note $note )
    {
        if ( !$this->notes->contains( $note ) ) {
            $this->notes->add( $note );
        }

        return $this;
    }

    /**
     * @param Note $note
     *
     * @return $this
     */
    public function removeNote( Note $note )
    {
        if ( $this->notes->contains( $note ) ) {
            $this->notes->removeElement( $note );
        }

        return $this;
    }

    /**
     * @param null $format
     *
     * @return \DateTime|string
     */
    public function getLastLogin( $format = NULL )
    {
        $last = $this->lastLogin;
        if ( empty( $last ) ) {
            $last = new \DateTime();
            $time = mktime( 0, 0, 0, 1, 1, 2000 );
            $last->setTimestamp( $time );
        }

        if ( !empty( $format ) ) {
            return $last->format( $format );
        }

        return $last;
    }

    /**
     * @ORM\Prepersist
     * @ORM\Preupdate
     */
    public function prepersist()
    {
        // lets sync up the sys roles to the user roles
        $sys_roles = $this->getSysRoles();
        foreach ( $sys_roles as $role ) {
            $rname = $role->getRoleName();
            $this->addRole( $rname );
        }

        // lets remove any roles which are no longer part of this user
        foreach ( $this->getRoles() as $role ) {
            if ( FALSE == ( $sys_role = $this->hasSysRole( $role ) ) ) {
                // this role is not in the sys roles
                $this->removeRole( $role );
            }
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getSysRoles()
    {
        return $this->sys_roles;
    }

    /**
     * @param $roles
     *
     * @return $this
     */
    public function setSysRoles( $roles )
    {
        $this->sys_roles = $roles;

        return $this;
    }

    /**
     * @param null $role_name
     *
     * @return bool
     */
    public function hasSysRole( $role_name = NULL )
    {
        foreach ( $this->getSysRoles() as $sys_role ) {
            if ( $sys_role->getName() == $role_name || $sys_role->getRoleName() == $role_name ) {
                return $sys_role;
            }
        }

        return FALSE;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
