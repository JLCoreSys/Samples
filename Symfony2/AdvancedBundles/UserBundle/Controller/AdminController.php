<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\UserEvent;
use CoreSys\UserBundle\Entity\User;

/**
 * Class AdminController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/users")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_users_index")
     * @Template()
     */
    public function indexAction()
    {
        $user       = new User();
        $dispatcher = $this->get( 'event_dispatcher' );
        $event      = new UserEvent( $user, $this->get( 'request' ) );
        $dispatcher->dispatch( CoreSysUserEvents::ADMIN_VIEW_USER_INDEX, $event );

        return array();
    }

    /**
     * @Route("/not_found", name="admin_users_user_not_found")
     * @Template()
     */
    public function userNotFoundAction()
    {
        return array();
    }

    /**
     * @Route("/{id}", name="admin_users_user", defaults={"id"="null"})
     * @Template()
     */
    public function viewUserAction( $id )
    {
        $repo = $this->getRepo( 'CoreSysUserBundle:User' );
        $user = $repo->findOneById( $id );
        if ( empty( $user ) ) {
            $user = $repo->findOneByUsername( $id );
        }
        if ( empty( $user ) ) {
            return $this->forward( 'CoreSysUserBundle:Admin:userNotFound' );
        }

        $extra_view = NULL;

        $event = new UserEvent( $user, $this->get( 'request' ) );
        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_VIEW_USER_VIEW );

        if ( $event->hasData() ) {
            $extra_view = $event->getViewStats();
        }

        return array( 'user' => $user, 'view_hook' => $extra_view );
    }
}
