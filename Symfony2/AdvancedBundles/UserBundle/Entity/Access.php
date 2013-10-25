<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Access
 *
 * @ORM\Table(name="user_access")
 * @ORM\Entity(repositoryClass="CoreSys\UserBundle\Entity\AccessRepository")
 */
class Access
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="access")
     * @ORM\JoinTable(name="user_role_access")
     */
    private $roles;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=128, nullable=true)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=128, nullable=true)
     */
    private $ip;

    /**
     * @var boolean
     *
     * @ORM\Column(name="anonymous", type="boolean", nullable=true)
     */
    private $anonymous;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=32, nullable=true)
     */
    private $channel;

    /**
     *
     */
    public function __construct()
    {
        $this->setRoles( new ArrayCollection() );
        $this->setActive( TRUE );
        $this->setAnonymous( FALSE );
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
     * Add role
     *
     * @param Role $role
     *
     * @return Access
     */
    public function addRole( Role $role = NULL )
    {
        if ( !empty( $role ) ) {
            if ( !$this->roles->contains( $role ) ) {
                $this->roles->add( $role );
            }
        }

        return $this;
    }

    /**
     * Remove role
     *
     * @param Role $role
     *
     * @return Access
     */
    public function removeRole( Role $role = NULL )
    {
        if ( !empty( $role ) ) {
            if ( $this->roles->contains( $role ) ) {
                $this->roles->removeElement( $role );
            }
        }

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
     * @return Access
     */
    public function setActive( $active )
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $path  = $this->getPath();
        $roles = array();
        foreach ( $this->getRoles() as $role ) {
            $roles[ ] = $role->getRole();
        }
        $ip      = $this->getIp();
        $host    = $this->getHost();
        $anon    = $this->getAnonymous();
        $channel = $this->getChannel();

        $return = array();

        if ( !empty( $path ) ) $return[ 'path' ] = $path;
        if ( !empty( $ip ) ) $return[ 'ip' ] = $ip;
        if ( !empty( $host ) ) $return[ 'host' ] = $host;
        if ( $anon ) {
            $roles[ ] = 'IS_AUTHENTICATED_ANONYMOUSLY';
        }
        if ( !empty( $channel ) ) $return[ 'channel' ] = $channel;
        $return[ 'roles' ] = $roles;

        return $return;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Access
     */
    public function setPath( $path )
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get roles
     *
     * @return ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param ArrayCollection $roles
     *
     * @return Access
     */
    public function setRoles( $roles )
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp( $ip )
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost( $host )
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getAnonymous()
    {
        return $this->anonymous === TRUE;
    }

    /**
     * @param mixed $anonymous
     */
    public function setAnonymous( $anonymous = NULL )
    {
        $this->anonymous = $anonymous === TRUE;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param null $channel
     *
     * @return $this
     */
    public function setChannel( $channel = NULL )
    {
        $this->channel = $channel;

        return $this;
    }
}
