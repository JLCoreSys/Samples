<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CoreSys\UserBundle\DependencyInjection\Compiler\AccessControlPass;

/**
 * Class CoreSysUserBundle
 * @package CoreSys\UserBundle
 */
class CoreSysUserBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build( ContainerBuilder $container )
    {
        parent::build( $container );
        $container->addCompilerPass( new AccessControlPass() );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
