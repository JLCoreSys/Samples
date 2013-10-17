<?php

namespace CoreSys\SiteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CoreSys\SiteBundle\DependencyInjection\Compiler\AddMultiParamConverterPass;

class CoreSysSiteBundle extends Bundle
{

    public function build( ContainerBuilder $container )
    {
        parent::build( $container );
//        $container->addCompilerPass( new AddMultiParamConverterPass() );
    }

}
