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
use CoreSys\SiteBundle\Entity\Slider;

/**
 * Class AdminController
 * @package CoreSys\SiteBundle\Controller
 * @Route("/admin/site")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_site_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect( $this->generateUrl( 'admin_site_settings' ) );
    }

    /**
     * @Route("/settings", name="admin_site_settings")
     * @Template()
     */
    public function settingsAction()
    {
        $config  = $this->getSiteSettings();
        $form    = $this->createForm( new SettingsType(), $config );
        $request = $this->get( 'request' );

        if ( $request->isMethod( 'POST' ) ) {
            $form->bind( $request );
            if ( $form->isValid() ) {
                $config = $form->getData();

                // purify the site description
                $purify      = $this->get( 'exercise_html_purifier.twig_extension' );
                $description = $config->getSiteDescription();
                $description = $purify->purify( $description, 'none' );
                $config->setSiteDescription( $description );

                $file = $config->getFile();
                if ( !empty( $file ) ) {
                    // we have an image upload here
                    $old = $config->getLogo();
                    if ( !empty( $old ) ) {
                        $this->remove( $old, array( 'site' ) );
                    }

                    $image = new Image();
                    $image->setFile( $file );
                    $image->uploadImage( FALSE );

                    $this->persist( $image );
                    $config->setLogo( $image );

                    $image->hasPublicImages( 'site' );
                }

                $files = $config->getFiles();
                if ( is_array( $files ) ) {
                    foreach ( $files as $file ) {
                        $image = new Image();
                        $image->setFile( $file );
                        $image->uploadImage( FALSE );
                        $this->persist( $image );
                        $config->addImage( $image );
                        $image->hasPublicImages( 'site' );
                    }
                }

                $this->persist( $config );
                $this->flush();
                $this->setSuccess( 'Successfully saved site settings.' );

                return $this->redirect( $this->generateUrl( 'admin_site_settings' ) );
            }
            else {
                $this->setError( 'Sorry, could not save site settings.' );
            }
        }

        $logo = $config->getLogo();
        if ( !empty( $logo ) ) {
            $logo->hasPublicImages( 'site' );
        }

        return array(
            'config' => $config,
            'form'   => $form->createView()
        );
    }

    /**
     * @Route("/sliders", name="admin_site_sliders")
     * @Template()
     */
    public function slidersAction()
    {
        $repo = $this->getRepo( 'CoreSysSiteBundle:Slider' );

        return array(
            'sliders' => $repo->findAll()
        );
    }

    /**
     * @Route("/admin_create_slider", name="admin_site_slider_create")
     * @Template()
     */
    public function createNewSliderAction()
    {
        $repo          = $this->getRepo( 'CoreSysSiteBundle:Slider' );
        $sliders       = $repo->findAll();
        $sliders_count = count( $sliders );

        $slider = new Slider();
        $form   = $this->createForm( new SliderType(), $slider );

        $request = $this->get( 'request' );
        if ( $request->isMethod( 'POST' ) ) {
            $form->bind( $request );
            if ( $form->isValid() ) {
                $slider = $form->getData();
                $slider->setPosition( $sliders_count );
                $active   = $slider->getActive();
                $position = $slider->getPosition();
                $file     = $slider->getFile();

                if ( !is_array( $file ) || count( $file ) == 1 ) {
                    $file = $slider->getFile();
                    $file = isset( $file[ 0 ] ) ? $file[ 0 ] : NULL;
                    if ( !empty( $file ) ) {
                        $old = $slider->getImage();
                        if ( !empty( $old ) ) {
                            $this->remove( $old, array( 'sliders' ) );
                            $slider->setImage( NULL );
                        }

                        $image = new Image();
                        $image->setFile( $file );
                        $image->uploadImage( FALSE );
                        $image->hasPublicImages( 'sliders', TRUE );
                        $this->persist( $image );

                        $slider->setImage( $image );
                        $this->persist( $slider );

                        $this->flush();

                        $this->setSuccess( 'Successfully added new slider image.' );
                    }
                    else {
                        $this->setError( 'You must include an image file with the slider.' );
                    }
                }
                else {
                    // this is multiple files, for multiple sliders
                    $files = $file;
                    foreach ( $files as $file ) {
                        $slider = new Slider();
                        $slider->setPosition( $position );
                        $position++;
                        $slider->setActive( $active );

                        if ( !empty( $file ) ) {
                            $old = $slider->getImage();
                            if ( !empty( $old ) ) {
                                $this->remove( $old, array( 'sliders' ) );
                                $slider->setImage( NULL );
                            }

                            $image = new Image();
                            $image->setFile( $file );
                            $image->uploadImage( FALSE );
                            $image->hasPublicImages( 'sliders', TRUE );
                            $this->persist( $image );

                            $slider->setImage( $image );
                            $this->persist( $slider );

                            $this->flush();

                            $this->setSuccess( 'Successfully added new slider image.' );
                        }
                        else {
                            $this->setError( 'You must include an image file with the slider.' );
                        }
                    }
                }
            }
            else {
                $this->setError( 'Sorry, we could not add your slider image.' );
            }
        }

        return $this->redirect( $this->generateUrl( 'admin_site_sliders' ) );
    }

    /**
     * @Route("/save_slider/{id}", name="admin_site_slider_save",  defaults={"id"="null"})
     * @ParamConverter("slider", class="CoreSys\SiteBundle\Entity\Slider")
     * @Template()
     */
    public function saveSliderAction( Slider $slider )
    {
        $form = $this->createForm( new SliderType(), $slider );

        $request = $this->get( 'request' );
        if ( $request->isMethod( 'POST' ) ) {
            $form->bind( $request );
            if ( $form->isValid() ) {
                $slider = $form->getData();
                $file   = $slider->getFile();
                if ( is_array( $file ) ) {
                    $file = isset( $file[ 0 ] ) ? $file[ 0 ] : NULL;
                }
                if ( !empty( $file ) ) {
                    $old = $slider->getImage();
                    if ( !empty( $old ) ) {
                        $this->remove( $old, array( 'sliders' ) );
                        $slider->setImage( NULL );
                    }

                    $image = new Image();
                    $image->setFile( $file );
                    $image->uploadImage( FALSE );
                    $image->hasPublicImages( 'sliders', TRUE );
                    $this->persist( $image );

                    $slider->setImage( $image );
                }

                $this->persist( $slider );

                $this->flush();

                $this->setSuccess( 'Successfully saved slider image.' );
            }
            else {
                $this->setError( 'Sorry, we could not save your slider image.' );
            }
        }

        return $this->redirect( $this->generateUrl( 'admin_site_sliders' ) );
    }

    /**
     * @Route("/remove_site_image/{id}", name="admin_site_settings_ajax_remove_image", defaults={"id"="null"})
     * @ParamConverter("image", class="CoreSys\MediaBundle\Entity\Image")
     * @Template()
     */
    public function removeSiteImage( Image $image )
    {
        $config = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $config->removeImage( $image );
        $this->remove( $image, array( 'site' ) );
        $this->persist( $config );
        $this->flush();

        echo json_encode( array( 'success' => TRUE ) );
        exit;
    }

    /**
     * @Route("/image_list", name="admin_site_ajax_image_list")
     * @Template()
     */
    public function imageListAction()
    {
        $repo   = $this->getRepo( 'CoreSysSiteBundle:Config' );
        $config = $repo->getConfig();
        $logo   = $config->getLogo();
        $images = $config->getImages();

        $assets = $this->get( 'templating.helper.assets' );
        $return = array();
        if ( !empty( $logo ) ) {
            $logo      = $assets->getUrl( $logo->getUrl( 'site', 'original', TRUE ) );
            $return[ ] = array( 'title' => 'Site Logo', 'value' => $logo );
        }

        $index = 1;
        foreach ( $images as $image ) {
            $image     = $assets->getUrl( $image->getUrl( 'site', 'original', TRUE ) );
            $return[ ] = array( 'title' => 'Image ' . $index, 'value' => $image );
            $index++;
        }

        echo json_encode( $return );
        exit;
    }
}
