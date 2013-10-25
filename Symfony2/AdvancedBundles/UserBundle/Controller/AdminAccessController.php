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
 * Class AdminAccessController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/userAccess")
 */
class AdminAccessController extends BaseController
{

    /**
     * @Route("/access", name="admin_users_access")
     * @Template()
     */
    public function indexAction()
    {
        $user       = new User();
        $dispatcher = $this->get( 'event_dispatcher' );
        $event      = new UserEvent( $user, $this->get( 'request' ) );
        $dispatcher->dispatch( CoreSysUserEvents::ADMIN_VIEW_ACCESS_INDEX, $event );

        return array();
    }

}
