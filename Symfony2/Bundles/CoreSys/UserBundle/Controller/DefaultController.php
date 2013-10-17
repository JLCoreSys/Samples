<?php

namespace CoreSys\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 * @package CoreSys\UserBundle\Controller
 *
 * @Route("/user")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="user_idx")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect( $this->generateUrl( 'home_index' ) );
    }
}
