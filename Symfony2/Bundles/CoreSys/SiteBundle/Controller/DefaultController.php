<?php

namespace CoreSys\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{

    /**
     * @Route("/terms", name="site_terms")
     * @Template()
     */
    public function termsAction()
    {
        $config = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();

        return array( 'config' => $config );
    }

    /**
     * @Route("/privacy", name="site_privacy")
     * @Template()
     */
    public function privacyAction()
    {
        $config = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();

        return array( 'config' => $config );
    }

    /**
     * @Route("/site", name="site_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
