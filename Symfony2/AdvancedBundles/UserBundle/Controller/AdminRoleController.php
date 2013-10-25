<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Controller;

use CoreSys\UserBundle\Event\RoleEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\UserEvent;
use CoreSys\UserBundle\Entity\User;

/**
 * Class AdminRolesController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/userRoles")
 */
class AdminRoleController extends BaseController
{

    /**
     * @Route("/", name="admin_users_roles")
     * @Template()
     */
    public function indexAction()
    {

        $event = new RoleEvent( NULL );
        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_VIEW_ROLE_INDEX );

        return array();
    }
}
