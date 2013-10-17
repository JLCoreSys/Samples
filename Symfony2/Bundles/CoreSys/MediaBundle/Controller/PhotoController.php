<?php

namespace CoreSys\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;

use CoreSys\MediaBundle\Entity\Image;

use Imagine\Gd\Imagine;
use Imagine\Gd\Image as IImage;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;

/**
 * Class PhotoController
 * @package CoreSys\MediaBundle\Controller
 * @Route("/p")
 */
class PhotoController extends BaseController
{

    /**
     * @Route("/tiny/{slug}", name="media_photo_tiny", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function tinyAction( $slug )
    {
        $this->photoAction( 'tiny', $slug );
        exit;
    }

    /**
     * @Route("/thumb/{slug}", name="media_photo_thumb", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function thumbAction( $slug )
    {
        $this->photoAction( 'thumb', $slug );
        exit;
    }

    /**
     * @Route("/small/{slug}", name="media_photo_small", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function smallAction( $slug )
    {
        $this->photoAction( 'small', $slug );
        exit;
    }

    /**
     * @Route("/medium/{slug}", name="media_photo_medium", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function mediumAction( $slug )
    {
        $this->photoAction( 'medium', $slug );
        exit;
    }

    /**
     * @Route("/large/{slug}", name="media_photo_large", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function largeAction( $slug )
    {
        $this->photoAction( 'large', $slug );
        exit;
    }

    /**
     * @Route("/original/{slug}", name="media_photo_original", defaults={"slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function originalAction( $slug )
    {
        $this->photoAction( 'original', $slug );
        exit;
    }

    /**
     * @Route("/{size}/{slug}", name="media_photo_size", defaults={"size"="null","slug"="null"}, requirements={"slug"=".*"})
     * @Template()
     */
    public function photoAction( $size, $slug )
    {
        $repo  = $this->getRepo( 'CoreSysMediaBundle:Image' );
        $image = $repo->locateImage( $slug );

        if ( empty( $image ) ) {
            echo 'File not found.';
        }
        else {
            $image->show( $size );
        }

        exit;
    }
}
