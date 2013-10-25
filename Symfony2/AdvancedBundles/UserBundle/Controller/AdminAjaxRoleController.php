<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Controller;

use CoreSys\UserBundle\CoreSysUserEvents;
use CoreSys\UserBundle\Event\RoleEvent;
use CoreSys\UserBundle\Form\UserAddType;
use CoreSys\UserBundle\Form\UserEditType;
use CoreSys\UserBundle\Form\UserType;
use CoreSys\UserBundle\Form\RoleType;
use CoreSys\UserBundle\Form\AccessType;
use CoreSys\UserBundle\Entity\User;
use CoreSys\UserBundle\Entity\Role;
use CoreSys\UserBundle\Entity\Access;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;

/**
 * Class AdminAjaxRoleController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/ajax/userRoles")
 */
class AdminAjaxRoleController extends BaseController
{

    /**
     * @Route("/data_tables", name="admin_ajax_users_roles_data_tables")
     * @Template()
     */
    public function rolesDataTablesAction()
    {
        $request = $this->get( 'request' );

        $sEcho          = $request->get( 'sEcho', '1' );
        $iColumns       = $request->get( 'iColumns', '6' );
        $sColumns       = $request->get( 'sColumns', NULL );
        $iDisplayStart  = $request->get( 'iDisplayStart', '0' );
        $iDisplayLength = $request->get( 'iDisplayLength', 50 );
        $sSearch        = $request->get( 'sSearch', NULL );
        $bRegex         = $request->get( 'bRegex', FALSE );
        $sSearch_0      = $request->get( 'sSearch_0', NULL );
        $bRegex_0       = $request->get( 'bRegex_0', FALSE );
        $bSearchable_0  = $request->get( 'bSearchable_0', TRUE );
        $sSearch_1      = $request->get( 'sSearch_1', NULL );
        $bRegex_1       = $request->get( 'bRegex_1', FALSE );
        $bSearchable_1  = $request->get( 'bSearchable_1', TRUE );
        $sSearch_2      = $request->get( 'sSearch_2', NULL );
        $bRegex_2       = $request->get( 'bRegex_2', FALSE );
        $bSearchable_2  = $request->get( 'bSearchable_2', TRUE );
        $sSearch_3      = $request->get( 'sSearch_3', NULL );
        $bRegex_3       = $request->get( 'bRegex_3', FALSE );
        $bSearchable_3  = $request->get( 'bSearchable_3', TRUE );
        $sSearch_4      = $request->get( 'sSearch_4', NULL );
        $bRegex_4       = $request->get( 'bRegex_4', FALSE );
        $bSearchable_4  = $request->get( 'bSearchable_4', TRUE );
        $sSearch_5      = $request->get( 'sSearch_5', NULL );
        $bRegex_5       = $request->get( 'bRegex_5', FALSE );
        $bSearchable_5  = $request->get( 'bSearchable_5', TRUE );
        $sSearch_6      = $request->get( 'sSearch_6', NULL );
        $bRegex_6       = $request->get( 'bRegex_6', FALSE );
        $bSearchable_6  = $request->get( 'bSearchable_6', TRUE );
        $iSortingCols   = $request->get( 'iSortingCols', 0 );
        $iSortCol_0     = $request->get( 'iSortCol_0', 0 );
        $sSortDir_0     = $request->get( 'sSortDir_0', 'asc' );
        $bSortable_0    = $request->get( 'bSortable_0', FALSE );
        $bSortable_1    = $request->get( 'bSortable_1', FALSE );
        $bSortable_2    = $request->get( 'bSortable_2', TRUE );
        $bSortable_3    = $request->get( 'bSortable_3', TRUE );
        $bSortable_4    = $request->get( 'bSortable_4', TRUE );
        $bSortable_5    = $request->get( 'bSortable_5', TRUE );
        $bSortable_6    = $request->get( 'bSortable_6', FALSE );

        $sort     = 'id';
        $sort_dir = 'desc';
        if ( $iSortingCols > 0 ) {
            if ( $iSortCol_0 !== NULL ) {
                $iSortCol_0 = intval( $iSortCol_0 );
                $cols       = array( '0' => 'id', '1' => 'id', '2' => 'name', '3' => 'role_name', '4' => 'parents', '5' => 'status' );
                if ( array_key_exists( $iSortCol_0, $cols ) ) {
                    $sort = $cols[ $iSortCol_0 ];
                }
            }
            if ( !empty( $sSortDir_0 ) ) {
                $sort_dir = $sSortDir_0;
            }
        }

        $search = NULL;
        if ( !empty( $sSearch ) ) {
            $search = $sSearch;
        }

        $repo  = $this->getRepo( 'CoreSysUserBundle:Role' );
        $total = $repo->getCount();

        $return = array(
            'sEcho'                => $sEcho,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => array()
        );

        $this->getRolesList( $return, $iDisplayStart, $iDisplayLength, $sort, $sort_dir, $search );

        echo json_encode( $return );
        exit;
    }

    /**
     * @param      $return
     * @param int  $offset
     * @param int  $length
     * @param null $sort
     * @param null $sort_dir
     * @param null $search
     */
    private function getRolesList( &$return, $offset = 0, $length = 0, $sort = NULL, $sort_dir = NULL, $search = NULL )
    {
        $repo        = $this->getRepo( 'CoreSysUserBundle:Role' );
        $roles       = $repo->search( $sort, $sort_dir, $offset, $length, $search, NULL );
        $roles_count = $repo->searchCount( $search, NULL );

        $return[ 'iTotalDisplayRecords' ] = $roles_count;

        $aaData = array();

        foreach ( $roles as $role ) {

            $parents = $role->getParents();
            $parent  = NULL;

            $i     = 0;
            $types = array( 'primary', 'success', 'info', 'warning', 'danger' );
            foreach ( $parents as $p ) {
                $parent .= '<span class="label label-' . $types[ $i ] . '">' . $p->getName() . '</span>';
                $i++;
                if ( $i >= count( $types ) ) {
                    $i = 0;
                }
            }

            if ( empty( $parent ) ) {
                $parent = '<span class="label label-default">None</span>';
            }

            $color = $role->getColor();
            if ( empty( $color ) ) {
                $color = '#00F';
            }

            $text = '#FFF';
            $test = str_replace( '#', '', $color );
            $r    = $g = $b = '';
            if ( strlen( $test ) == 3 ) {
                $r = substr( $test, 0, 1 ) . substr( $test, 0, 1 );
                $g = substr( $test, 1, 1 ) . substr( $test, 1, 1 );
                $b = substr( $test, 2, 1 ) . substr( $test, 2, 1 );
            }
            else {
                $r = substr( $test, 0, 2 );
                $g = substr( $test, 2, 2 );
                $b = substr( $test, 3, 2 );
            }
            $r   = $this->reverseHex( $r );
            $g   = $this->reverseHex( $g );
            $b   = $this->reverseHex( $b );
            $rgb = $r + $g + $b;
            if ( $rgb <= 350 ) {
                $text = '#FFF';
            }
            else {
                $text = '#000';
            }

            $data      = array(
                '<input id="select-role-' . $role->getId() . '" data-rid="' . $role->getId() . '" data-name="' . $role->getName() . '" type="checkbox" class="uniform">',
                $role->getId(),
                $role->getName(),
                '<span class="label" style="background:' . $color . ';color:' . $text . '">' . $role->getRoleName() . '</span>',
                $parent,
                $role->getUsersCount(),
                '<i class="icon-' . ( $role->getSwitch() ? 'check green' : 'remove red' ) . '"></i>',
                '<i class="icon-' . ( $role->getActive() ? 'check green' : 'remove red' ) . '"></i>',
                $this->buildRoleTableRowOptions( $role )
            );
            $aaData[ ] = $data;
        }

        $return[ 'aaData' ] = $aaData;
    }

    /**
     * @param $hex
     *
     * @return int
     */
    function reverseHex( $hex )
    {
        $p1 = strtolower( substr( $hex, 0, 1 ) );
        $p2 = strtolower( substr( $hex, 1, 1 ) );
        if ( is_numeric( $p1 ) ) {
            $p1 = intval( $p1 );
        }
        else {
            if ( $p1 == 'a' ) $p1 = 10;
            else if ( $p1 == 'b' ) $p1 = 11;
            else if ( $p1 == 'c' ) $p1 = 12;
            else if ( $p1 == 'd' ) $p1 = 13;
            else if ( $p1 == 'e' ) $p1 = 14;
            else if ( $p1 == 'f' ) $p1 = 15;
        }
        $p1 = abs( 15 - $p1 );

        if ( is_numeric( $p2 ) ) {
            $p2 = intval( $p2 );
        }
        else {
            if ( $p2 == 'a' ) $p2 = 10;
            else if ( $p2 == 'b' ) $p2 = 11;
            else if ( $p2 == 'c' ) $p2 = 12;
            else if ( $p2 == 'd' ) $p2 = 13;
            else if ( $p2 == 'e' ) $p2 = 14;
            else if ( $p2 == 'f' ) $p2 = 15;
        }

        return intval( $p1 ) + intval( $p2 );
    }

    /**
     * @param Role $role
     *
     * @return null|string
     */
    private function buildRoleTableRowOptions( Role &$role = NULL )
    {
        if ( empty( $role ) ) {
            return NULL;
        }

        $rid  = $role->getId();
        $name = $role->getName();

        $menu = '<div class="btn-group">
                  <button type="button" class="role-actions btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Actions <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:editRole(' . $rid . ',\'' . $name . '\')"><i class="icon-edit" title="Edit ' . $name . '"></i> Edit</a></li>
                    <li><a href="javascript:removeRole(' . $rid . ',\'' . $name . '\')"><i class="icon-remove red" title="Remove ' . $name . '"></i> Remove</a></li>
                  </ul>
                </div>';

        return $menu;
    }

    /**
     * @Route("/new", name="admin_ajax_users_roles_new", defaults={"id"="null"})
     * @Route("/edit/{id}", name="admin_ajax_users_roles_edit", defaults={"id"="null"})
     * @Template()
     */
    public function roleFormAction( $id )
    {
        $id   = intval( $id );
        $repo = $this->getRepo( 'CoreSysUserBundle:Role' );
        $role = $repo->findOneById( $id );
        $new  = TRUE;
        if ( empty( $role ) ) {
            $action = $this->generateUrl( 'admin_ajax_users_roles_save_new' );
            $title  = 'Create New Role';
            $new    = FALSE;
            $role   = new Role();
        }
        else {
            $action = $this->generateUrl( 'admin_ajax_users_roles_save_role', array( 'id' => $role->getId() ) );
            $title  = 'Edit Role ' . $role->getName();
        }

        $form = $this->createForm( new RoleType(), $role );

        return array( 'form' => $form->createView(), 'title' => $title, 'action' => $action, 'new' => $new );
    }

    /**
     * @Route("/save_new", name="admin_ajax_users_roles_save_new")
     * @Template()
     */
    public function saveNewRoleAction()
    {
        $role  = new Role();
        $form  = $this->createForm( new RoleType(), $role );
        $form2 = $this->createForm( new RoleType(), $role );

        $form2->handleRequest( $this->get( 'request' ) );
        if ( $form2->isValid() ) {
            $name = $form2->getData()->getName();
            if ( empty( $name ) ) {
                $this->echoJsonError( 'Please enter a role name.' );
                exit;
            }
            else if ( $this->roleExists( $name ) ) {
                $this->echoJsonError( 'That role name already exists.' );
                exit;
            }
        }

        $result = $this->saveRoleForm( $form, $role );
        if ( $result ) {
            $this->echoJsonSuccess( 'Added new role' );
        }
        else {
            $this->echoJsonError( 'Could not add new role' );
        }
        exit;
    }

    /**
     * @param null $role_name
     * @param null $id
     *
     * @return bool
     */
    public function roleExists( $role_name = NULL, $id = NULL )
    {
        if ( empty( $role_name ) ) {
            return TRUE;
        }

        $repo = $this->getRepo( 'CoreSysUserBundle:Role' );
        $role = $repo->findOneByName( $role_name );

        if ( !empty( $role ) && !empty( $id ) ) {
            if ( $role->getId() == $id ) {
                $role = NULL;
                // this is the same role, we are ok
            }
        }

        return !empty( $role );
    }

    /**
     * @param      $form
     * @param Role $role
     *
     * @return bool
     */
    private function saveRoleForm( &$form, Role &$role = NULL )
    {
        $request = $this->get( 'request' );
        $form->handleRequest( $request );
        if ( $form->isValid() ) {
            // handle the role save options here
            $role = $form->getData();

            $this->persist( $role );
            $this->flush();

            $event = new RoleEvent( $role );
            $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_SAVE_ROLE );

            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @Route("/save_role/{id}", name="admin_ajax_users_roles_save_role", defaults={"id"="null"})
     * @ParamConverter("role", class="CoreSysUserBundle:Role")
     * @Template()
     */
    public function saveRoleAction( Role $role )
    {
        $form  = $this->createForm( new RoleType(), $role );
        $form2 = $this->createForm( new RoleType(), $role );

        $form2->handleRequest( $this->get( 'request' ) );
        if ( $form2->isValid() ) {
            $data = $form2->getData();
            $name = $form2->getData()->getName();
            if ( empty( $name ) ) {
                $this->echoJsonError( 'Please enter a role name.' );
                exit;
            }
            else if ( $this->roleExists( $name, $data->getId() ) ) {
                $this->echoJsonError( 'That role name already exists.' );
                exit;
            }
        }

        $result = $this->saveRoleForm( $form, $role );
        if ( $result ) {
            $this->echoJsonSuccess( 'Saved role' );
        }
        else {
            $this->echoJsonError( 'Could not save role' );
        }
        exit;
    }

    /**
     * @Route("/remove/{id}", name="admin_ajax_users_role_remove", defaults={"id"="null"})
     * @ParamConverter("role", class="CoreSysUserBundle:Role")
     * @Template()
     */
    public function removeRoleAction( Role $role )
    {
        $event = new RoleEvent( $role );

        $name = $role->getName();

        $event = new RoleEvent( $role );
        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_REMOVE_ROLE_BEFORE );

        $this->remove( $role );
        $this->flush();

        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_SAVE_ROLE );

        $this->echoJsonSuccess( 'Successfully removed role "' . $name . '"' );
        exit;
    }

    /**
     * @Route("/remove", name="admin_ajax_users_roles_remove", defaults={"id"="null"})
     * @Template()
     */
    public function removeRolesAction()
    {
        $repo     = $this->getRepo( 'CoreSysUserBundle:Role' );
        $request  = $this->get( 'request' );
        $role_ids = $request->get( 'role_ids' );
        $role_ids = is_array( $role_ids ) ? $role_ids : array();
        $names    = array();

        foreach ( $role_ids as $rid ) {
            $role = $repo->findOneById( $rid );
            if ( !empty( $role ) ) {
                $names[ ] = '"' . $role->getName() . '"';
                $event    = new RoleEvent( $role );
                $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_REMOVE_ROLE_BEFORE );
                $this->remove( $role );
            }
        }

        $event = new RoleEvent( NULL );
        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_REMOVE_ROLE );

        $this->flush();

        $this->echoJsonSuccess( 'Removed roles: ' . implode( ',', $names ) );
        exit;
    }
}