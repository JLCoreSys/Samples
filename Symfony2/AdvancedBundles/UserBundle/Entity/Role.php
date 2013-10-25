<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="user_role")
 * @ORM\Entity(repositoryClass="CoreSys\UserBundle\Entity\RoleRepository")
 */
class Role implements RoleInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role_name", type="string", length=64)
     */
    private $roleName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="children")
     * @ORM\JoinTable(name="user_role_parents",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     *      )
     */
    private $parents;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="parents")
     */
    private $children;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Access", mappedBy="roles")
     */
    private $access;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Access", mappedBy="roles")
     */
    private $users;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=12, nullable=true)
     */
    private $color;

    /**
     * @var boolean
     *
     * @ORM\Column(name="switch", type="boolean", nullable=true)
     */
    private $switch;

    /**
     *
     */
    public function __construct()
    {
        $this->setChildren( new ArrayCollection() );
        $this->setAccess( new ArrayCollection() );
        $this->setParent( NULL );
        $this->setActive( TRUE );
        $this->setParents( new ArrayCollection() );
        $this->setUsers( new ArrayCollection() );
        $this->setColor( '#428bca' );
        $this->setSwitch( FALSE );
    }

    /**
     * Set parent
     *
     * @param Role $parent
     *
     * @return Role
     */
    public function setParent( Role $parent = NULL )
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSwitch()
    {
        return $this->switch === TRUE;
    }

    /**
     * @param bool $switch
     *
     * @return $this
     */
    public function setSwitch( $switch = FALSE )
    {
        $this->switch = $switch === TRUE;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        if ( empty( $this->color ) ) {
            $this->color = '#428bca';
        }

        return $this->color;
    }

    /**
     * @param null $color
     *
     * @return $this
     */
    public function setColor( $color = NULL )
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addUser( User $user = NULL )
    {
        if ( !$this->users->contains( $user ) ) {
            $this->users->add( $user );
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function removeUser( User $user = NULL )
    {
        if ( $this->users->contains( $user ) ) {
            $this->users->removeElement( $user );
        }

        return $this;
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName( $name )
    {
        $name       = trim( $name );
        $this->name = $name;
        $role_name  = 'ROLE_' . $this->name;
        $this->setRoleName( $role_name );

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Role
     */
    public function setActive( $active )
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Role | NULL
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Has parent
     *
     * @return bool
     */
    public function hasParent()
    {
        return !empty( $this->parent );
    }

    /**
     * Get children
     *
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param ArrayCollection $children
     *
     * @return Role
     */
    public function setChildren( $children )
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add child
     *
     * @param Role $child
     *
     * @return Role
     */
    public function addChild( Role $child = NULL )
    {
        if ( !empty( $child ) ) {
            if ( !$this->children->contains( $child ) ) {
                $this->children->add( $child );
            }
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param Role $child
     *
     * @return Role
     */
    public function removeChild( Role $child = NULL )
    {
        if ( !empty( $child ) ) {
            if ( $this->children->contains( $child ) ) {
                $this->children->removeElement( $child );
            }
        }

        return $this;
    }

    /**
     * Get access
     *
     * @return ArrayCollection
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set access
     *
     * @param ArrayCollection $access
     *
     * @return Role
     */
    public function setAccess( $access )
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Add access
     *
     * @param Access $child
     *
     * @return Role
     */
    public function addAccess( Access $access = NULL )
    {
        if ( !empty( $access ) ) {
            if ( !$this->access->contains( $access ) ) {
                $this->access->add( $access );
            }
        }

        return $this;
    }

    /**
     * Remove access
     *
     * @param Access access
     *
     * @return Role
     */
    public function removeAccess( Access $access = NULL )
    {
        if ( !empty( $access ) ) {
            if ( $this->access->contains( $access ) ) {
                $this->access->removeElement( $access );
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param $parents
     *
     * @return $this
     */
    public function setParents( $parents )
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addParent( Role $role = NULL )
    {
        if ( !$this->parents->contains( $role ) ) {
            $this->parents->add( $role );
        }

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function removeParent( Role $role = NULL )
    {
        if ( $this->parents->contains( $role ) ) {
            $this->parents->removeElement( $role );
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getUsersCount()
    {
        return count( $this->getUsers() );
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $users
     */
    public function setUsers( $users )
    {
        $this->users = $users;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->getRoleName();
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     *
     * @return Role
     */
    public function setRoleName( $roleName )
    {
        $roleName = strtoupper( $roleName );
        $roleName = str_replace( ' ', '', $roleName );

        $this->roleName = $roleName;

        return $this;
    }
}
