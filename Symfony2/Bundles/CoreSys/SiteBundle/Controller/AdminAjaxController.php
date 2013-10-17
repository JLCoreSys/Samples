<?php

namespace CoreSys\SiteBundle\Controller;

use CoreSys\MediaBundle\Entity\Image;
use CoreSys\SiteBundle\Form\SliderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\SiteBundle\Form\SettingsType;
use CoreSYs\SiteBundle\Entity\Slider;

/**
 * Class AdminController
 * @package CoreSys\SiteBundle\Controller
 * @Route("/admin/ajax/site")
 */
class AdminAjaxController extends BaseController
{

    /**
     * @Route("/new_slider_form", name="admin_site_ajax_new_slider_form")
     * @Template()
     */
    public function newSliderFormAction()
    {
        $slider = new Slider();
        $form   = $this->createForm( new SliderType(), $slider );

        return array( 'form' => $form->createView() );
    }

    /**
     * @Route("/edit_slider_form/{id}", name="admin_site_ajax_edit_slider_form", defaults={"id"="null"})
     * @ParamConverter("slider", class="CoreSys\SiteBundle\Entity\Slider")
     * @Template()
     */
    public function editSliderFormAction( Slider $slider )
    {
        $form = $this->createForm( new SliderType(), $slider );

        return array( 'form' => $form->createView(), 'slider' => $slider );
    }

    /**
     * @Route("/preview_slider/{id}", name="admin_site_ajax_preview_slider", defaults={"id"="null"})
     * @ParamConverter("slider", class="CoreSys\SiteBundle\Entity\Slider")
     * @Template()
     */
    public function previewSliderAction( Slider $slider )
    {
        return array( 'slider' => $slider );
    }

    /**
     * @Route("/remove_slider/{id}", name="admin_site_ajax_remove_slider", defaults={"id"="null"})
     * @ParamConverter("slider", class="CoreSys\SiteBundle\Entity\Slider")
     * @Template()
     */
    public function removeSliderAction( Slider $slider )
    {
        echo $this->echoJsonError( 'Unknown command' );
    }

    /**
     * @Route("/save_slider_order", name="admin_site_ajax_save_slider_order")
     * @Template()
     */
    public function saveSliderOrderAction()
    {
        $request = $this->get( 'request' );
        if ( $request->isMethod( 'POST' ) ) {
            $order = $request->get( 'order' );
            $repo  = $this->getRepo( 'CoreSysSiteBundle:Slider' );

            $count = 0;
            $sids  = array();
            foreach ( $order as $sid ) {
                $slide = $repo->findOneById( $sid );
                if ( !empty( $slide ) ) {
                    $sids[ ] = $sid;
                    $slide->setPosition( $count++ );
                    $this->persist( $slide );
                }
            }

            $this->flush();

            $this->echoJsonSuccess( 'Success', array( 'order' => $order, 'sids' => $sids ) );
            exit;
        }

        $this->echoJsonError( 'No post detected' );
        exit;
    }

    /**
     * @Route("/remove_checked_sliders", name="admin_site_ajax_remove_checked_sliders")
     * @Template()
     */
    public function removeCheckedSlidersAction()
    {
        $request = $this->get( 'request' );
        if ( $request->isMethod( 'POST' ) ) {
            $sids = $request->get( 'sids' );
            $repo = $this->getRepo( 'CoreSysSiteBundle:Slider' );

            foreach ( $sids as $sid ) {
                $slide = $repo->findOneById( $sid );
                if ( !empty( $slide ) ) {
                    $image = $slide->getImage();
                    $image->remove();
                    $this->remove( $image, array( 'slides' ) );
                    $this->remove( $slide );
                }
            }

            $this->flush();

            foreach ( $repo->findAll() as $i => $slide ) {
                $slide->setPosition( $i );
                $this->persist( $slide );
            }

            $this->flush();

            $this->echoJsonSuccess( 'Success', array( 'sids' => $sids ) );
            exit;
        }

        $this->echoJsonError( 'No post detected' );
        exit;
    }

    public function echoJsonSuccess( $msg = NULL, $params = array() )
    {
        $output = array( 'success' => TRUE, 'msg' => $msg );
        foreach ( $params as $key => $val ) {
            $output[ $key ] = $val;
        }
        echo json_encode( $output );
    }

    public function echoJsonError( $msg = NULL, $params = array() )
    {
        $output = array( 'success' => FALSE, 'msg' => $msg );
        foreach ( $params as $key => $val ) {
            $output[ $key ] = $val;
        }
        echo json_encode( $output );
    }
}