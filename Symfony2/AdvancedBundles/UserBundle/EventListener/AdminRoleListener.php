<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\EventListener;

use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\RoleEvent;
use CoreSys\UserBundle\Entity\User;
use CoreSys\UserBundle\Entity\Role;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AdminRoleListener
 * @package CoreSys\UserBundle\EventListener
 */
class AdminRoleListener implements EventSubscriberInterface
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
            CoreSysUserEvents::ADMIN_SAVE_ROLE          => 'onSaveRole',
            CoreSysUserEvents::ADMIN_REMOVE_ROLE_BEFORE => 'onBeforeRemoveRole',
            CoreSysUserEvents::ADMIN_REMOVE_ROLE        => 'onRemoveRole'
        );
    }

    /**
     * @param RoleEvent $event
     */
    public function onSaveRole( RoleEvent $event )
    {
        $role = $event->getRole();
        /* can do any work on the role here */
        $this->dumpYml( $event );
    }

    /**
     * @param RoleEvent $event
     */
    public function onBeforeRemoveRole( RoleEvent $event )
    {
        $role = $event->getRole();
        $this->removeTheRoleFromUsers( $role );
    }

    /**
     * @param RoleEvent $event
     */
    public function onRemoveRole( RoleEvent $event )
    {
        $role = $event->getRole();
        // do nothing at this point

        $this->dumpYml( $event );
    }

    /**
     * @param Role $role
     */
    public function removeTheRoleFromUsers( Role $role )
    {
        $repo     = $this->container->get( 'doctrine.orm.entity_manager' )->getRepository( 'CoreSysUserBundle:User' );
        $users    = $repo->getUsersWithRole( $role );
        $flush    = FALSE;
        $roleName = $role->getRole();

        foreach ( $users as $user ) {
            $flush = TRUE;
            $user->removeRole( $roleName );
            $user->removeSysRole( $role );
        }

        if ( $flush ) {
            $manager = $this->container->get( 'fos_user.user_manager' );
            $manager->updateUser( $user );
        }
    }

    /**
     * @param $event
     */
    public function dumpYml( $event )
    {
        $manager = $this->container->get( 'core_sys_user.role_manager' );
        $manager->dumpRolesYml();
        $this->dispatchComplete( $event );
    }

    /**
     * @param $event
     */
    private function dispatchComplete( $event )
    {
        $dispatcher = $this->container->get( 'event_dispatcher' );
        $dispatcher->dispatch( CoreSysUserEvents::ADMIN_CHANGED_ROLE, $event );
    }
}