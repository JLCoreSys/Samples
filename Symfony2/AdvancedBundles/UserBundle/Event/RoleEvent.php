<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Event;

use Symfony\Component\Security\Core\Role\RoleInterface;
use CoreSys\UserBundle\Entity\Role;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoleEvent
 * @package CoreSys\UserBundle\Event
 */
class RoleEvent extends Event
{

    /**
     * @var null
     */
    private $role = NULL;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var array
     */
    private $view = array();

    /**
     * @param RoleInterface $role
     */
    public function __construct( RoleInterface $role = NULL )
    {
        $this->setRole( $role );
    }

    /**
     * @return null
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function setRole( Role $role = NULL )
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param null $view
     *
     * @return $this
     */
    public function setView( $view = NULL )
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param null $content
     *
     * @return $this
     */
    public function addViewStats( $content = NULL )
    {
        if ( !empty( $content ) ) {
            $view = $this->getView();
            if ( !isset( $view[ 'stats' ] ) ) {
                $view[ 'stats' ] = array();
            }
            $view[ 'stats' ][ ] = '<li>' . $content . '</li>';
            $this->view         = $view;
        }

        return $this;
    }

    /**
     * @return null
     */
    public function getViewStats()
    {
        return isset( $this->view[ 'stats' ] ) ? $this->view[ 'stats' ] : NULL;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return count( $this->data ) > 0 || count( $this->view ) > 0;
    }
}
