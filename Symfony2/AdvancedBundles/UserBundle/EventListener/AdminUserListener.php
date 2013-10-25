<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\EventListener;

use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class AdminUserListener
 * @package CoreSys\UserBundle\EventListener
 */
class AdminUserListener implements EventSubscriberInterface
{

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $router;

    /**
     * @param UrlGeneratorInterface $router
     */
    public function __construct( UrlGeneratorInterface $router )
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            CoreSysUserEvents::ADMIN_VIEW_USER_VIEW => 'onViewAdminUser'
        );
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onRequest( FilterControllerEvent $event )
    {

    }

    /**
     * @param UserEvent $event
     */
    public function onViewAdminUser( UserEvent $event )
    {
        $stats = 'This is the user ' . $event->getUser()->getUsername();
        $event->addViewStats( $stats );
    }
}