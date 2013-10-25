<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\EventListener;

use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\AccessEvent;
use CoreSys\UserBundle\Event\RoleEvent;
use CoreSys\UserBundle\Entity\User;
use CoreSys\UserBundle\Entity\Access;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AdminAccessListener
 * @package CoreSys\UserBundle\EventListener
 */
class AdminAccessListener implements EventSubscriberInterface
{

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $router;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param UrlGeneratorInterface $router
     * @param ContainerInterface    $container
     */
    public function __construct( UrlGeneratorInterface $router, ContainerInterface $container )
    {
        $this->router    = $router;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            CoreSysUserEvents::ADMIN_CHANGED_ROLE  => 'onRoleChanged',
            CoreSysUserEvents::ADMIN_SAVE_ACCESS   => 'onAccessSaved',
            CoreSysUserEvents::ADMIN_REMOVE_ACCESS => 'onAccessRemoved'
        );
    }

    /**
     * @param AccessEvent $event
     */
    public function onAccessSaved( AccessEvent $event )
    {
        $access = $event->getAccess();
        $this->dumpYml( $event );
    }

    /**
     * @param AccessEvent $event
     */
    public function onAccessRemoved( AccessEvent $event )
    {
        $access = $event->getAccess();
        $this->dumpYml( $event );
    }

    /**
     * In the event that a role has changed, we need to rewrite the yml file
     * for the access controls
     *
     * @param RoleEvent $event
     */
    public function onRoleChanged( RoleEvent $event )
    {
        $role = $event->getRole();
        $this->dumpYml( $event );
    }

    /**
     * @param $event
     */
    public function dumpYml( $event )
    {
        $manager = $this->container->get( 'core_sys_user.access_manager' );
        $manager->dumpAccessYml();
    }
}