<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Model;

use CoreSys\SiteBundle\Model\BaseManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RolesManager
 * @package CoreSys\UserBundle\Model
 */
class RolesManager extends BaseManager
{

    /**
     * @return array
     */
    function getAllRoles()
    {
        return $this->getRepo( 'CoreSysUserBundle:Role' )->findAll();
    }

    /**
     * @return array
     */
    function getRoleNamePairs()
    {
        $return = array();
        foreach ( $this->getAllRoles() as $role ) {
            $return[ $role->getId() ] = $role->getName();
        }

        return $return;
    }

    /**
     *
     */
    function dumpRolesYml()
    {
        $yml    = $this->getRoleHierarchy( TRUE );
        $fs     = new Filesystem();
        $root   = $this->get( 'kernel' )->getRootDir();
        $config = $root . DIRECTORY_SEPARATOR . 'config';
        $file   = $config . DIRECTORY_SEPARATOR . 'roles.yml';

        $fs->dumpFile( $file, $yml, 0777 );

        $contents = file_get_contents( $file );
    }

    /**
     * @param bool $yml_return
     *
     * @return array|string
     */
    function getRoleHierarchy( $yml_return = FALSE )
    {
        $roles     = $this->getAllRoles();
        $hierarchy = array();

        foreach ( $roles as $role ) {
            $role_hierarchy = array();
            foreach ( $role->getParents() as $parent ) {
                $role_hierarchy[ ] = $parent->getRoleName();
            }
            if ( count( $role->getParents() ) == 0 ) {
                $role_hierarchy = 'ROLE_USER';
            }

            if ( $role->getSwitch() ) {
                if ( !in_array( 'ROLE_ALLOWED_TO_SWITCH', $role_hierarchy ) ) {
                    $role_hierarchy[ ] = 'ROLE_ALLOWED_TO_SWITCH';
                }
            }

            $hierarchy[ $role->getRoleName() ] = $role_hierarchy;
        }

        $yaml = Yaml::dump( $hierarchy, 1 );

        return $yml_return ? $yaml : $hierarchy;
    }
}