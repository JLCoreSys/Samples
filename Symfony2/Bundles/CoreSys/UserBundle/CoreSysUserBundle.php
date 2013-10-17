<?php

namespace CoreSys\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoreSysUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
