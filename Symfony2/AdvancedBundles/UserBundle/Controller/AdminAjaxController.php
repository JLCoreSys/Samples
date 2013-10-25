<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Controller;

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
use CoreSys\UserBundle\CoreSysUserEvents;

/**
 * Class AdminAjaxController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/ajax/users")
 */
class AdminAjaxController extends BaseController
{

    /** START USERS PAGE AJAX REQUESTS */
    /**
     * @Route("/data_tables", name="admin_ajax_users_data_tables")
     * @Template()
     */
    public function dataTablesAction()
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
        $date_range     = $request->get( 'date_range', NULL );
        $role_search    = $request->get( 'role_search', NULL );

        $role_repo = $this->getRepo( 'CoreSysUserBundle:Role' );
        $role      = $role_repo->findOneById( $role_search );

        $sort     = 'created_at';
        $sort_dir = 'desc';
        if ( $iSortingCols > 0 ) {
            if ( $iSortCol_0 !== NULL ) {
                $iSortCol_0 = intval( $iSortCol_0 );
                $cols       = array( '0' => 'created_at', '1' => 'id', '2' => 'username', '3' => 'email', '4' => 'created_at', '5' => 'updated_at', '6' => 'S6' );
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

        $from = NULL;
        $to   = NULL;
        if ( !empty( $date_range ) ) {
            list( $from, $to ) = explode( '-', $date_range );
            $from_ts = strtotime( $from );
            $to_ts   = strtotime( $to );

            $from = new \DateTime();
            $from->setTimestamp( $from_ts );
            $from->setTime( 0, 0, 0 );

            $to = new \DateTime();
            $to->setTimestamp( $to_ts );
            $to->setTime( 23, 59, 59 );
        }

        $repo  = $this->getRepo( 'CoreSysUserBundle:User' );
        $total = $repo->getUsersCount();

        $return = array(
            'sEcho'                => $sEcho,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => array()
        );

        $this->getUsersList( $return, $iDisplayStart, $iDisplayLength, $sort, $sort_dir, $search, $from, $to, $role );

        echo json_encode( $return );
        exit;
    }

    private function getUsersList( &$return, $offset = 0, $length = 0, $sort = NULL, $sort_dir = NULL, $search = NULL, $from = NULL, $to = NULL, Role $role = NULL )
    {
        $repo        = $this->getRepo( 'CoreSysUserBundle:User' );
        $users       = $repo->search( $sort, $sort_dir, $offset, $length, $search, NULL, $from, $to, $role );
        $users_total = $repo->searchCount( $search, NULL, $from, $to, $role );

        $return[ 'iTotalDisplayRecords' ] = $users_total;

        $aaData = array();

        foreach ( $users as $user ) {
            $data      = array(
                '<input id="select-user-' . $user->getId() . '" data-uid="' . $user->getId() . '" data-login="' . $user->getUsername() . '" type="checkbox" class="uniform">',
                $user->getId(),
                $user->getUsername(),
                $user->getEmail(),
                $user->getCreatedAt( 'M d, Y g:i a' ),
                $this->getRoleTags( $user ),
                $this->buildUserTableRowOptions( $user )
            );
            $aaData[ ] = $data;
        }

        $return[ 'aaData' ] = $aaData;
    }

    private function buildUserTableRowOptions( User &$user = NULL )
    {
        if ( empty( $user ) ) {
            return NULL;
        }

        $uid   = $user->getId();
        $login = $user->getUsername();

        $menu = '<div class="btn-group">
                  <button type="button" class="user-actions btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Actions <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:viewUser(' . $uid . ',\'' . $login . '\')"><i class="icon-search" title="View ' . $login . '"></i> View</a></li>
                    <li><a href="javascript:editUser(' . $uid . ',\'' . $login . '\')"><i class="icon-edit" title="Edit ' . $login . '"></i> Edit</a></li>
                    <li><a href="javascript:removeUser(' . $uid . ',\'' . $login . '\')"><i class="icon-remove red" title="Remove ' . $login . '"></i> Remove</a></li>
                  </ul>
                </div>';

        return $menu;
    }

    private function getRoleTags( User &$user = NULL )
    {
        if ( empty( $user ) ) {
            return NULL;
        }

        $return = array();
        $omit   = array();

        $i     = 0;
        $types = array( 'primary', 'success', 'info', 'warning', 'danger' );
        foreach ( $user->getSysRoles() as $role ) {
            $color     = $role->getColor();
            $role      = $role->getName();
            $role      = strtolower( trim( str_replace( 'ROLE_', '', $role ) ) );
            $role      = ucwords( str_replace( '_', ' ', $role ) );
            $return[ ] = '<span class="label" style="background:' . $color . '">' . $role . '</span>';
            $i++;
            if ( $i >= count( $types ) ) {
                $i = 0;
            }
        }

        return implode( $return );
    }

    /**
     * @Route("/new", name="admin_ajax_users_new", defaults={"id"="null"})
     * @Route("/edit/{id}", name="admin_ajax_users_edit", defaults={"id"="null"})
     * @Template()
     */
    public function userFormAction( $id )
    {
        $id   = intval( $id );
        $repo = $this->getRepo( 'CoreSysUserBundle:User' );
        $user = $repo->findOneById( $id );
        $new  = TRUE;
        if ( empty( $user ) ) {
            $action = $this->generateUrl( 'admin_ajax_users_save_new' );
            $title  = 'Create New User';
            $new    = FALSE;
            $user   = new User();
        }
        else {
            $action = $this->generateUrl( 'admin_ajax_users_save_user', array( 'id' => $user->getId() ) );
            $title  = 'Edit User ' . $user->getUsername();
        }

        if ( $new ) {
            $form = $this->createForm( new UserType(), $user );
        }
        else {
            $form = $this->createForm( new UserType(), $user );
        }

        return array( 'form' => $form->createView(), 'title' => $title, 'action' => $action, 'new' => $new );
    }

    /**
     * @Route("/save_new", name="admin_ajax_users_save_new")
     * @Template()
     */
    public function saveNewUserAction()
    {
        $manager = $this->get( 'fos_user.user_manager' );

        $user = $manager->createUser();
        $form = $this->createForm( new UserType(), $user );

        $form2 = $this->createForm( new UserType(), $user );
        $form2->handleRequest( $this->get( 'request' ) );
        if ( $form2->isValid() ) {
            $data     = $form2->getData();
            $username = $data->getUsername();
            if ( empty( $username ) ) {
                $this->echoJsonError( 'Must enter a username.' );
                exit;
            }
            $email = $data->getEmail();
            if ( empty( $email ) ) {
                $this->echoJsonError( 'Must enter a valid email address.' );
                exit;
            }
            $plainPassword = $data->getPlainPassword();
            if ( empty( $plainPassword ) || strlen( $plainPassword ) < 6 ) {
                $this->echoJsonError( 'Must enter a password of at least 6 characters in length.' );
                exit;
            }
        }

        $result = $this->saveUserForm( $form );
        if ( $result ) {
            $this->echoJsonSuccess( 'Successfully added new user' );
        }
        else {
            $errors = $form->getErrorsAsString();
            $this->echoJsonError( 'Could not add new user<br>' . $errors );
        }

        exit;
    }

    /**
     * @Route("/save_user/{id}", name="admin_ajax_users_save_user", defaults={"id"="null"})
     * @ParamConverter("user", class="CoreSysUserBundle:User")
     * @Template()
     */
    public function saveUserAction( $user )
    {
        $form = $this->createForm( new UserType(), $user );

        $result = $this->saveUserForm( $form );
        if ( $result ) {
            $this->echoJsonSuccess( 'Successfully saved user' );
        }
        else {
            $this->echoJsonError( 'Could not save user' );
        }

        exit;
    }

    private function saveUserForm( &$form, User &$user = NULL )
    {
        $request = $this->get( 'request' );
        $form->handleRequest( $request );
        if ( $form->isValid() ) {
            $user    = $form->getData();
            $manager = $this->get( 'fos_user.user_manager' );
            $manager->updateUser( $user );

            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @Route("/user/remove/{id}", name="admin_ajax_users_remove_user", defaults={"id"="null"})
     * @ParamConverter("user", class="CoreSysUserBundle:User")
     * @Template()
     */
    public function removeUserAction( User $user )
    {
        $username = $user->getUsername();
        $this->remove( $user );
        $this->flush();

        $this->echoJsonSuccess( 'Successfully removed "' . $username . '"' );
        exit;
    }

    /**
     * @Route("/users/remove", name="admin_ajax_users_remove_selected")
     * @Template()
     */
    public function removeUsersAction()
    {
        $repo     = $this->getRepo( 'CoreSysUserBundle:User' );
        $request  = $this->get( 'request' );
        $user_ids = $request->get( 'user_ids' );
        $user_ids = is_array( $user_ids ) ? $user_ids : array();
        $names    = array();

        foreach ( $user_ids as $uid ) {
            $user = $repo->findOneById( $uid );
            if ( !empty( $user ) ) {
                $names[ ] = '"' . $user->getUsername() . '"';
                $this->remove( $user );
            }
        }

        $this->flush();

        $this->echoJsonSuccess( 'Removed users: ' . implode( ',', $names ) );
        exit;
    }
}
