<?php

namespace CoreSys\SiteBundle\Model;

use CoreSys\SiteBundle\Model\BaseManager;

/**
 * Class ContentManager
 * @package CoreSys\SiteBundle\Model
 */
class ContentManager extends BaseManager
{

    /**
     * @param null $block
     *
     * @return null
     */
    public function processContentBlock( $block = NULL )
    {
        $this->processContentBlockPaths( $block );

        return $block;
    }

    /**
     * @param null $block
     */
    public function processContentBlockPaths( &$block = NULL )
    {
        $matches = array();
        $count   = 0;
        while ( preg_match( '/\[path\](.*)\[\/path\]/', $block, $matches ) && $count <= 30 ) {

            $path = isset( $matches[ 1 ] ) ? $matches[ 1 ] : NULL;
            $path = strtolower( trim( $path ) );
            if ( !empty( $path ) ) {
                $replace = '[path]' . $path . '[/path]';
                $with    = $this->generateUrl( $path, array(), TRUE );
                $block   = str_replace( $replace, $with, $block );
            }
            $count++;
        }

        $block = str_replace( '&nbsp;&nbsp;', '&nbsp;', $block );

        $self = $_SERVER[ 'PHP_SELF' ];
        if ( !strstr( $self, 'app_dev.php' ) ) {
            $block = str_replace( 'app_dev.php/', '', $block );
            $block = str_replace( '/app_dev.php', '', $block );
        }
    }
}