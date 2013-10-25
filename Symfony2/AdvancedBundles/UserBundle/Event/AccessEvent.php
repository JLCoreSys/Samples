<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Event;

use CoreSys\UserBundle\Entity\Access;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccessEvent
 * @package CoreSys\UserBundle\Event
 */
class AccessEvent extends Event
{

    /**
     * @var null
     */
    private $access = NULL;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var array
     */
    private $view = array();

    /**
     * @param Access $access
     */
    public function __construct( Access $access = NULL )
    {
        $this->setAccess( $access );
    }

    /**
     * @return null
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param Access $access
     *
     * @return $this
     */
    public function setAccess( Access $access = NULL )
    {
        $this->access = $access;

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
