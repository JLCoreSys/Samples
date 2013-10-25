<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Twig;

use CoreSys\SiteBundle\Twig\BaseExtension;

/**
 * Common user extensions to be used in conjunction with
 * the CoreSys Bundles
 *
 * Class RoleExtension
 * @package CoreSys\SiteBundle\Twig
 */
class RoleExtension extends BaseExtension
{

    /**
     * @var string
     */
    protected $name = 'role_extension';

    /**
     * @return array
     */
    public function getFilters()
    {
        return array();
    }

    /**
     * @return \CoreSys\SiteBundle\Twig\repo|\Doctrine\ORM\EntityRepository|null
     */
    public function getUserRepo()
    {
        return $this->getRepo( 'CoreSysUserBundle:User' );
    }

    /**
     * @return \CoreSys\SiteBundle\Twig\repo|\Doctrine\ORM\EntityRepository|null
     */
    public function getRoleRepo()
    {
        return $this->getRepo( 'CoreSysUserBundle:Role' );
    }

    /**
     * @return mixed|object
     */
    public function getUserManager()
    {
        return $this->get( 'core_sys_user.user_manager' );
    }

    /**
     * @return mixed|object
     */
    public function getRoleManager()
    {
        return $this->get( 'core_sys_user.role_manager' );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'userRoles'     => new \Twig_Function_Method( $this, 'getUserRoles' ),
            'userRolePairs' => new \Twig_Function_Method( $this, 'getUserRolePairs' ),
        );
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->getRoleManager()->getAllRoles();
    }

    /**
     * @param bool   $json
     * @param string $id_text
     * @param string $name_text
     *
     * @return float|string
     */
    public function getUserRolePairs( $json = FALSE, $id_text = 'id', $name_text = 'name' )
    {
        $pairs = $this->getRoleManager()->getRoleNamePairs();
        if ( $json !== TRUE ) {
            return $pairs;
        };
        $return = array();
        foreach ( $pairs as $id => $rname ) {
            $return[ ] = array( $id_text => $id, $name_text => $rname );
        }

        return json_encode( $return );
    }

}