<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Model;

use CoreSys\SiteBundle\Model\BaseManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;

/**
 * Class AccessManager
 * @package CoreSys\UserBundle\Model
 */
class AccessManager extends BaseManager
{

    /**
     *
     */
    function dumpAccessYml()
    {
        $yml    = $this->getAccessControlStructure( TRUE );
        $fs     = new Filesystem();
        $root   = $this->get( 'kernel' )->getRootDir();
        $config = $root . DIRECTORY_SEPARATOR . 'config';
        $file   = $config . DIRECTORY_SEPARATOR . 'access_control.yml';

        $fs->dumpFile( $file, $yml, 0777 );
    }

    /**
     * @param bool $return_yml
     *
     * @return array|string
     */
    function getAccessControlStructure( $return_yml = FALSE )
    {
        $return = array();
        $repo   = $this->getRepo( 'CoreSysUserBundle:Access' );
        foreach ( $repo->findAll() as $access ) {
            $data      = $access->getData();
            $return[ ] = $data;
        }

        $yml = Yaml::dump( $return, 1 );

        if ( $return_yml ) {
            return $yml;
        }

        return $return;
    }
}