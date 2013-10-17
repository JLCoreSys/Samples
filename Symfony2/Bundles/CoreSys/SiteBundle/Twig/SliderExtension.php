<?php

namespace CoreSys\SiteBundle\Twig;

use CoreSys\SiteBundle\Twig\BaseExtension;

class SliderExtension extends BaseExtension
{

    /**
     * @var string
     */
    protected $name = 'slider_extension';

    /**
     * @return array
     */
    public function getFilters()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'nivoCss'    => new \Twig_Function_Method( $this, 'getNivoCss', array( 'is_safe' => array( 'html' ) ) ),
            'nivoImages' => new \Twig_Function_Method( $this, 'getNivoImages', array( 'is_safe' => array( 'html' ) ) ),
            'nivoJs'     => new \Twig_Function_Method( $this, 'getNivoJs', array( 'is_safe' => array( 'html' ) ) )
        );
    }

    public function getNivoCss()
    {
        $assets = $this->get( 'templating.helper.assets' );
        $files  = array(
            'slider/default/default',
            'slider/light/light',
            'slider/dark/dark',
            'slider/bar/bar',
            'nivo-slider'
        );

        $files = implode( ',', $files );
        $src   = $assets->getUrl( 'constellation/css/mini.php' ) . '?files=' . $files;

        $css = '<link href="' . $src . '" rel="stylesheet" type="text/css" />';

        return $css;
    }

    public function getNivoJs()
    {
        $assets = $this->get( 'templating.helper.assets' );
        $files  = array(
            'libs/jquery.nivo.slider.js',
            'slider.js'
        );
        $files  = implode( ',', $files );
        $src    = $assets->getUrl( 'constellation/js/mini.php' ) . '?files=' . $files;

        $script = '<script type="text/javascript" src="' . $src . '"></script>';

        return $script;
    }

    public function getNivoImages( $count = NULL )
    {
        $count   = intval( $count ) == 0 ? 999999 : intval( $count );
        $repo    = $this->getRepo( 'CoreSysSiteBundle:Slider' );
        $sliders = $repo->getActiveSliders( $count );

        $assets = $this->get( 'templating.helper.assets' );

        $return = array();
        foreach ( $sliders as $slider ) {
            $image = $slider->getImage();
            if ( !empty( $image ) ) {
                $img_src   = $assets->getUrl( $image->getUrl( 'sliders', 'original', TRUE ) );
                $img       = '<img src="' . $img_src . '" style="max-width: 100%" border="0" />';
                $return[ ] = $img;
            }
        }

        return implode( $return );
    }
}