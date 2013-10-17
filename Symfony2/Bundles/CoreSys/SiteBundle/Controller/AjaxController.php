<?php

namespace CoreSys\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\SiteBundle\Form\SettingsType;

/**
 * Class AdminController
 * @package CoreSys\SiteBundle\Controller
 * @Route("/common/ajax")
 */
class AjaxController extends BaseController
{

    /**
     * @Route("/check_string_for_html", name="site_ajax_check_for_html")
     * @Template()
     */
    public function checkStringForHtmlAction()
    {
        $request = $this->get( 'request' );
        $string  = $request->get( 'string' );
        $allowed = $request->get( 'allowed' );
        $type    = $request->get( 'type', 'custom' );

        $purify = $this->get( 'exercise_html_purifier.twig_extension' );
        $clean  = $purify->purify( $string, $type );

        $string = preg_replace( '/[\r\n]+/', '', $string );
        $clean  = preg_replace( '/[\r\n]+/', '', $clean );

        $html = $string != $clean;

        echo json_encode( array(
                              'success'  => TRUE,
                              'has_html' => $html,
                              'original' => $string,
                              'clean'    => $clean
                          ) );
        exit;
    }
}
