<?php

namespace CoreSys\SiteBundle\Twig;

use CoreSys\SiteBundle\Twig\BaseExtension;

/**
 * Class SiteExtension
 * @package CoreSys\SiteBundle\Twig
 */
class SiteExtension extends BaseExtension
{

    /**
     * @var string
     */
    protected $name = 'site_extension';

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'count'      => new \Twig_Filter_Method( $this, 'getFilterArrayCount' ),
            'dump'       => new \Twig_Filter_Method( $this, 'getFilterVarDump' ),
            'strip'      => new \Twig_Filter_Method( $this, 'getFilterStrip' ),
            'ucwords'    => new \Twig_Filter_Method( $this, 'getFilterUcWords' ),
            'strtolower' => new \Twig_Filter_Method( $this, 'getStrtolower' ),
            'nospaces'   => new \Twig_Filter_Method( $this, 'getNoSpaces' ),
        );
    }

    /**
     * @param null $string
     *
     * @return mixed
     */
    public function getNoSpaces( $string = NULL )
    {
        $string = str_replace( ' ', '', $string );

        return $string;
    }

    /**
     * @param array $array
     *
     * @return int
     */
    public function getFilterArrayCount( $array = array() )
    {
        $array = is_array( $array ) ? $array : array();

        return count( $array );
    }

    /**
     * @param null $obj
     *
     * @return string
     */
    public function getFilterVarDump( $obj = NULL )
    {
        return $this->getVarDumpFunc( $obj );
    }

    /**
     * get the vardump of an item
     *
     * @param mixed $item
     *
     * @return string
     */
    public function getVarDumpFunc( $item = NULL )
    {
        ob_start();
        var_dump( $item );
        $contents = ob_get_clean();

        return $contents;
    }

    /**
     * @param null $string
     *
     * @return mixed
     */
    public function getFilterStrip( $string = NULL )
    {
        $string = preg_replace( '/[^a-zA-Z0-9.\s]+/', '', $string );

        return $string;
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getFilterUcWords( $string = NULL )
    {
        return ucwords( $string );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'varDump'             => new \Twig_Function_Method( $this, 'getVarDumpFunc' ),
            'getHost'             => new \Twig_Function_Method( $this, 'getHost' ),
            'date'                => new \Twig_Function_Method( $this, 'getDate' ),
            'dateFuture'          => new \Twig_Function_Method( $this, 'getFutureDate' ),
            'strtolower'          => new \Twig_Function_Method( $this, 'getStrtolower' ),
            'intval'              => new \Twig_Function_Method( $this, 'getIntval' ),
            'substr'              => new \Twig_Function_Method( $this, 'getSubstr' ),
            'contains'            => new \Twig_Function_Method( $this, 'getContains' ),
            'randomFromArray'     => new \Twig_Function_Method( $this, 'getRandomFromArray' ),
            'browser'             => new \Twig_Function_Method( $this, 'getBrowser' ),
            'isDev'               => new \Twig_Function_Method( $this, 'isDev' ),
            'siteSetting'         => new \Twig_Function_Method( $this, 'getSiteSetting', array( 'is_safe' => array( 'html' ) ) ),
            'str_replace'         => new \Twig_Function_Method( $this, 'getStrReplace' ),
            'rtrim'               => new \Twig_Function_Method( $this, 'getRtrim' ),
            'fugue'               => new \Twig_Function_Method( $this, 'getFugueImage', array(
                'is_safe' => array( 'html' )
            ) ),
            'webapp'              => new \Twig_Function_Method( $this, 'getWebAppImage', array(
                'is_safe' => array( 'html' ),
            ) ),
            'routeContains'       => new \Twig_Function_Method( $this, 'getRouteContains' ),
            'companyAddress'      => new \Twig_Function_Method( $this, 'getCompanyAddress', array( 'is_safe' => array( 'html' ) ) ),
            'companyHours'        => new \Twig_Function_Method( $this, 'getCompanyHours', array( 'is_safe' => array( 'html' ) ) ),
            'processContentBlock' => new \Twig_Function_Method( $this, 'processContentBlock', array( 'is_safe' => array( 'html' ) ) ),
            'linksList'           => new \Twig_Function_Method( $this, 'getLinksList', array( 'is_safe' => array( 'html' ) ) )
        );
    }

    /**
     * @param bool $json
     *
     * @return array|bool|float|int|string
     */
    public function getLinksList( $json = FALSE )
    {
        $basic      = array();
        $adv        = array();
        $router     = $this->getContainer()->get( 'router' );
        $collection = $router->getRouteCollection();
        $allRoutes  = $collection->all();

        foreach ( $allRoutes as $route => $params ) {
            if ( strstr( $route, 'index' ) ) {
                $title           = str_replace( '_index', '', $route );
                $title           = ucwords( str_replace( '_', ' ', strtolower( trim( $title ) ) ) );
                $basic[ $title ] = $route;
                $adv[ $route ]   = array( 'title' => $title, 'value' => $this->generateUrl( $route ) );
            }
        }

        if ( $json ) {
            ksort( $adv );

            return json_encode( $adv );
        }

        return $basic;
    }

    /**
     * @param null $block
     *
     * @return mixed
     */
    public function processContentBlock( $block = NULL )
    {
        $manager = $this->get( 'core_sys_site.content_manager' );

        return $manager->processContentBlock( $block );
    }

    /**
     * @return string
     */
    public function getCompanyAddress()
    {
        $templating = $this->getContainer()->get( 'templating' );
        $template   = $templating->render( 'CoreSysSiteBundle:Default:Address.html.twig' );

        return html_entity_decode( $template );
    }

    /**
     * @return string
     */
    public function getCompanyHours()
    {
        $repo   = $this->getRepo( 'CoreSysSiteBundle:Config' );
        $config = $repo->getConfig();

        $templating = $this->getContainer()->get( 'templating' );
        $template   = $templating->render( 'CoreSysSiteBundle:Default:Hours.html.twig', array( 'config' => $config ) );

        return html_entity_decode( $template );
    }

    /**
     * @param null $num
     *
     * @return int
     */
    public function getIntval( $num = NULL )
    {
        return intval( $num );
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getStrtolower( $string = NULL )
    {
        return strtolower( $string );
    }

    /**
     * @param null $string
     *
     * @return bool|string
     */
    public function getRouteContains( $string = NULL )
    {
        if ( empty( $string ) ) {
            return FALSE;
        }

        $request = $this->getContainer()->get( 'request' );
        $route   = $request->get( '_route' );
        $route   = strtolower( trim( $route ) );

        $string = strtolower( trim( $string ) );

        return strstr( $route, $string );
    }

    /**
     * @param      $img
     * @param int  $size
     * @param null $class
     *
     * @return string
     */
    public function getWebAppImage( $img, $size = 24, $class = NULL )
    {
        $width  = $size;
        $height = $size;
        $img    = 'constellation/images/icons/web-app/' . $size . '/' . $img . '.png';
        $src    = $this->getContainer()->get( 'templating.helper.assets' )->getUrl( $img, NULL );
        $return = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" class="' . $class . '" />';

        return $return;
    }

    /**
     * @param      $img
     * @param int  $width
     * @param int  $height
     * @param null $class
     *
     * @return string
     */
    public function getFugueImage( $img, $width = 16, $height = 16, $class = NULL )
    {
        $img    = 'constellation/images/icons/fugue/' . $img . '.png';
        $src    = $this->getContainer()->get( 'templating.helper.assets' )->getUrl( $img, NULL );
        $return = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" class="' . $class . '" />';

        return $return;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $_SERVER[ 'HTTP_HOST' ];
    }

    /**
     * @param $string
     * @param $trim
     *
     * @return string
     */
    public function getRtrim( $string, $trim )
    {
        return rtrim( $string, $trim );
    }

    /**
     * @param $match
     * @param $replace
     * @param $string
     *
     * @return mixed
     */
    public function getStrReplace( $match, $replace, $string )
    {
        $string = str_replace( $match, $replace, $string );

        return $string;
    }

    /**
     *
     */
    public function getSiteSetting( $var = NULL, $default = NULL )
    {
        $repo   = $this->getEntityManager()->getRepository( 'CoreSysSiteBundle:Config' );
        $config = $repo->getConfig();

        $function = strtolower( trim( $var ) );
        $function = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $var ) ) );
        $function = 'get' . $function;

        if ( method_exists( $config, $function ) ) {
            return $config->$function();
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isDev()
    {
        $uri = $_SERVER[ 'REQUEST_URI' ];
        if ( stristr( $uri, '/dev' ) ) {
            return TRUE;
        }
        elseif ( stristr( $uri, 'app_dev.php' ) ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param bool $group_ie
     *
     * @return mixed|string
     */
    public function getBrowser( $group_ie = FALSE )
    {
        $server = $_SERVER;
        $agent  = isset( $server[ 'HTTP_USER_AGENT' ] ) ? $server[ 'HTTP_USER_AGENT' ] : 'chrome';
        if ( stristr( $agent, 'chrome' ) ) {
            return 'chrome';
        }
        else if ( stristr( $agent, 'msie' ) ) {
            if ( !$group_ie ) {
                for ( $i = 6; $i <= 12; $i++ ) {
                    $test = 'msie ' . $i;
                    if ( stristr( $agent, $test ) ) {
                        return str_replace( ' ', '', $test );
                    }
                }
            }

            return 'ie';
        }
        else if ( stristr( $agent, 'opera' ) ) {
            return 'opera';
        }
        else if ( stristr( $agent, 'firefox' ) ) {
            return 'firefox';
        }

        return 'chrome';
    }

    /**
     * @param array $array
     *
     * @return null
     */
    public function getRandomFromArray( $array = array() )
    {
        if ( !is_array( $array ) ) {
            $array = array();
        }
        shuffle( $array );
        foreach ( $array as $item ) {
            return $item;
        }

        return NULL;
    }

    /**
     * @param      $string
     * @param null $search
     *
     * @return bool|string
     */
    public function getContains( $string, $search = NULL )
    {
        if ( $search === NULL ) {
            return TRUE;
        }

        return strstr( $string, $search );
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getUcWords( $string = NULL )
    {
        return ucwords( $string );
    }

    /**
     * @param      $string
     * @param int  $start
     * @param null $length
     * @param bool $dots
     *
     * @return string
     */
    public function getSubstr( $string, $start = 0, $length = NULL, $dots = TRUE )
    {
        if ( empty( $length ) ) {
            return $string;
        }

        $length = intval( $length );
        $state  = intval( $start );

        if ( strlen( $string ) > $length && $dots ) {
            $dots = '...';
        }
        else {
            $dots = '';
        }

        return substr( $string, $start, $length ) . $dots;
    }

    /**
     * @param      $format
     * @param null $days
     * @param null $months
     * @param null $years
     *
     * @return string
     */
    public function getFutureDate( $format, $days = NULL, $months = NULL, $years = NULL )
    {
        $day   = date( 'd' );
        $month = date( 'n' );
        $year  = date( 'Y' );
        if ( !empty( $days ) ) {
            $day += intval( $days );
        }
        if ( !empty( $months ) ) {
            $month += intval( $months );
        }
        if ( !empty( $years ) ) {
            $year += intval( $years );
        }
        $time = mktime( 0, 0, 0, $month, $day, $year );

        return date( $format, $time );
    }

    /**
     * @param $format
     *
     * @return string
     */
    public function getDate( $format )
    {
        return date( $format );
    }
}