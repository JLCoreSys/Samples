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
use CoreSys\UserBundle\Event\AccessEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\UserBundle\CoreSysUserEvents;

/**
 * Class AdminAjaxAccessController
 * @package CoreSys\UserBundle\Controller
 * @Route("/admin/ajax/usersAccess")
 */
class AdminAjaxAccessController extends BaseController
{

    /**
     * @Route("/data_tables", name="admin_ajax_users_access_data_tables")
     * @Template()
     */
    public function accessDataTablesAction()
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
                $cols       = array( '0' => 'id', '1' => 'id', '2' => 'path', '4' => 'status' );
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

        $repo  = $this->getRepo( 'CoreSysUserBundle:Access' );
        $total = $repo->getCount();

        $return = array(
            'sEcho'                => $sEcho,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => array()
        );

        $this->getAccessList( $return, $iDisplayStart, $iDisplayLength, $sort, $sort_dir, $search );

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
    private function getAccessList( &$return, $offset = 0, $length = 0, $sort = NULL, $sort_dir = NULL, $search = NULL )
    {
        $repo         = $this->getRepo( 'CoreSysUserBundle:Access' );
        $access       = $repo->search( $sort, $sort_dir, $offset, $length, $search, NULL );
        $access_count = $repo->searchCount( $search, NULL );

        $return[ 'iTotalDisplayRecords' ] = $access_count;

        $aaData = array();

        foreach ( $access as $access ) {

            $roles = $access->getRoles();
            $role  = NULL;

            $i     = 0;
            $types = array( 'primary', 'success', 'info', 'warning', 'danger' );
            foreach ( $roles as $r ) {
                $role .= '<span class="label label-' . $types[ $i ] . '">' . $r->getName() . '</span>';
                $i++;
                if ( $i >= count( $types ) ) {
                    $i = 0;
                }
            }

            if ( empty( $role ) ) {
                $role = '<span class="label label-default">Anyone</span>';
            }

            $data      = array(
                '<input id="select-access-' . $access->getId() . '" data-rid="' . $access->getId() . '" data-name="' . $access->getPath() . '" type="checkbox" class="uniform">',
                $access->getId(),
                $access->getPath(),
                $role,
                $access->getHost(),
                $access->getIp(),
                '<i class="icon-' . ( $access->getAnonymous() ? 'check green' : 'remove red' ) . '"></i>',
                '<i class="icon-' . ( $access->getActive() ? 'check green' : 'remove red' ) . '"></i>',
                $this->buildAccessTableRowOptions( $access )
            );
            $aaData[ ] = $data;
        }

        $return[ 'aaData' ] = $aaData;
    }

    private function buildAccessTableRowOptions( Access &$access = NULL )
    {
        if ( empty( $access ) ) {
            return NULL;
        }

        $aid  = $access->getId();
        $path = $access->getPath();

        $menu = '<div class="btn-group">
                  <button type="button" class="access-actions btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Actions <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" access="menu">
                    <li><a href="javascript:editAccess(' . $aid . ',\'' . $path . '\')"><i class="icon-edit" title="Edit ' . $path . '"></i> Edit</a></li>
                    <li><a href="javascript:removeAccess(' . $aid . ',\'' . $path . '\')"><i class="icon-remove red" title="Remove ' . $path . '"></i> Remove</a></li>
                  </ul>
                </div>';

        return $menu;
    }

    /**
     * @Route("/new", name="admin_ajax_users_access_new", defaults={"id"="null"})
     * @Route("/edit/{id}", name="admin_ajax_users_access_edit", defaults={"id"="null"})
     * @Template()
     */
    public function accessFormAction( $id )
    {
        $id     = intval( $id );
        $repo   = $this->getRepo( 'CoreSysUserBundle:Access' );
        $access = $repo->findOneById( $id );
        $new    = TRUE;
        if ( empty( $access ) ) {
            $action = $this->generateUrl( 'admin_ajax_users_access_save_new' );
            $title  = 'Create New Access';
            $new    = FALSE;
            $access = new Access();
        }
        else {
            $action = $this->generateUrl( 'admin_ajax_users_access_save_access', array( 'id' => $access->getId() ) );
            $title  = 'Edit Access ' . $access->getPath();
        }

        $form = $this->createForm( new AccessType(), $access );

        return array( 'form' => $form->createView(), 'title' => $title, 'action' => $action, 'new' => $new );
    }

    /**
     * @Route("/save_new", name="admin_ajax_users_access_save_new")
     * @Template()
     */
    public function saveNewAccessAction()
    {
        $access = new Access();
        $form   = $this->createForm( new AccessType(), $access );
        $form2  = $this->createForm( new AccessType(), $access );

        $form2->handleRequest( $this->get( 'request' ) );
        if ( $form2->isValid() ) {
            $path = $form2->getData()->getPath();
            if ( empty( $path ) ) {
                $this->echoJsonError( 'Please enter a access path.' );
                exit;
            }
            else if ( $this->accessExists( $path ) ) {
                $this->echoJsonError( 'That access name already exists.' );
                exit;
            }
        }

        $result = $this->saveAccessForm( $form, $access );
        if ( $result ) {
            $this->echoJsonSuccess( 'Added new access' );
        }
        else {
            $this->echoJsonError( 'Could not add new access' );
        }
        exit;
    }

    public function accessExists( $access_path = NULL, $id = NULL )
    {
        if ( empty( $access_path ) ) {
            return TRUE;
        }

        $repo   = $this->getRepo( 'CoreSysUserBundle:Access' );
        $access = $repo->findOneByPath( $access_path );

        if ( !empty( $access ) && !empty( $id ) ) {
            if ( $access->getId() == $id ) {
                $access = NULL;
                // this is the same access, we are ok
            }
        }

        return !empty( $access );
    }

    private function saveAccessForm( &$form, Access &$access = NULL )
    {
        $request = $this->get( 'request' );
        $form->handleRequest( $request );
        if ( $form->isValid() ) {
            // handle the access save options here
            $access = $form->getData();

            $this->persist( $access );
            $this->flush();

            $event = new AccessEvent( $access );
            $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_SAVE_ACCESS );

            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @Route("/save_access/{id}", name="admin_ajax_users_access_save_access", defaults={"id"="null"})
     * @ParamConverter("access", class="CoreSysUserBundle:Access")
     * @Template()
     */
    public function saveAccessAction( Access $access )
    {
        $form  = $this->createForm( new AccessType(), $access );
        $form2 = $this->createForm( new AccessType(), $access );

        $form2->handleRequest( $this->get( 'request' ) );
        if ( $form2->isValid() ) {
            $data = $form2->getData();
            $path = $form2->getData()->getPath();
            if ( empty( $path ) ) {
                $this->echoJsonError( 'Please enter a access path.' );
                exit;
            }
            else if ( $this->accessExists( $path, $data->getId() ) ) {
                $this->echoJsonError( 'That access path already exists.' );
                exit;
            }
        }

        $result = $this->saveAccessForm( $form, $access );
        if ( $result ) {
            $this->echoJsonSuccess( 'Saved access' );
        }
        else {
            $this->echoJsonError( 'Could not save access' );
        }
        exit;
    }

    /**
     * @Route("/remove/{id}", name="admin_ajax_users_access_remove", defaults={"id"="null"})
     * @ParamConverter("access", class="CoreSysUserBundle:Access")
     * @Template()
     */
    public function removeAccessAction( Access $access )
    {
        $event = new AccessEvent( $access );

        $path = $access->getPath();
        $this->remove( $access );
        $this->flush();

        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_REMOVE_ACCESS );

        $this->echoJsonSuccess( 'Successfully removed access "' . $path . '"' );
        exit;
    }

    /**
     * @Route("/remove_selected", name="admin_ajax_users_access_remove_selected", defaults={"id"="null"})
     * @Template()
     */
    public function removeSelectedAccessAction()
    {
        $repo       = $this->getRepo( 'CoreSysUserBundle:Access' );
        $request    = $this->get( 'request' );
        $access_ids = $request->get( 'access_ids' );
        $access_ids = is_array( $access_ids ) ? $access_ids : array();
        $paths      = array();

        foreach ( $access_ids as $rid ) {
            $access = $repo->findOneById( $rid );
            if ( !empty( $access ) ) {
                $paths[ ] = '"' . $access->getPath() . '"';
                $this->remove( $access );
            }
        }

        $this->flush();

        $event = new AccessEvent( NULL );
        $this->dispatchEvent( $event, CoreSysUserEvents::ADMIN_REMOVE_ACCESS );

        $this->echoJsonSuccess( 'Removed access: ' . implode( ',', $paths ) );
        exit;
    }


}