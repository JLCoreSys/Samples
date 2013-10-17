<?php

namespace CoreSys\MediaBundle\Model;

use CoreSys\SiteBundle\Model\BaseManager;
use CoreSys\MediaBundle\Entity\Image;

/**
 * Class ImageManager
 * @package CoreSys\MediaBundle\Model
 */
class ImageManager extends BaseManager
{

    /**
     * Cleanup any and all folders, files ..etc
     */
    public function fullCleanup()
    {
        $this->cleanUploadsFolder();
        $this->checkPublicImageFilesForCorrespondingEntity();
        $this->checkImageFilesForCorrespondingEntity();
    }

    /**
     * @param int $time
     */
    public function cleanUploadsFolder( $time = 1440 )
    {
        $upload_folder = $this->getUploadFolder();
        $this->clearOldFiles( $upload_folder, $time );
    }

    /**
     * @param null $folder
     * @param int  $time
     */
    public function clearOldFiles( $folder = NULL, $time = 1440 )
    {
        $now        = time();
        $time       = intval( $time );
        $time       = $time < 600 ? 600 : $time;
        $time_check = $now - $time;

        if ( is_dir( $folder ) ) {
            $dir = opendir( $folder );
            if ( $dir ) {
                while ( FALSE !== ( $file = readdir( $dir ) ) ) {
                    if ( $file != '.' && $file != '..' ) {
                        $filename = $folder . DIRECTORY_SEPARATOR . $file;
                        if ( is_file( $filename ) ) {
                            $mtime = filemtime( $filename );
                            if ( $mtime <= $time_check ) {
                                @unlink( $filename );
                            }
                        }
                        else if ( is_dir( $filename ) ) {
                            $this->clearOldFiles( $filename, $time );
                        }
                    }
                }
                closedir( $dir );
            }
        }
    }

    /**
     * Double check to make sure public images are available behind the root as well.
     * Do not want orphaned images
     */
    public function checkPublicImageFilesForCorrespondingEntity()
    {
        $public_folder = $this->getWebFolder() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ads';
        $this->checkImageFilesForCorrespondingEntity( $public_folder, array() );
    }

    /**
     * @param null  $folder
     * @param array $seen
     */
    public function checkImageFilesForCorrespondingEntity( $folder = NULL, $seen = array() )
    {
        if ( empty( $folder ) ) {
            $folder = $this->getMediaImagesFolder();
        }

        $seen = is_array( $seen ) ? $seen : array();

        $omit = array( 'small', 'medium', 'large', 'thumb', 'tiny', 'teenie', 'xlarge', 'xxlarge', 'xxxlarge' );

        if ( is_dir( $folder ) ) {
            $dir = opendir( $folder );
            if ( $dir ) {
                while ( FALSE !== ( $file = readdir( $dir ) ) ) {
                    if ( $file != '.' && $file != '..' ) {
                        $filename = $folder . DIRECTORY_SEPARATOR . $file;
                        if ( is_file( $filename ) ) {
                            $index = pathinfo( $filename, PATHINFO_FILENAME );
                            if ( !in_array( $index, $seen ) ) {
                                $entity = $this->locateImageEntityByFilename( $file );
                                if ( empty( $entity ) ) {
                                    foreach ( $omit as $path ) {
                                        $path = str_replace( $file, $path . DIRECTORY_SEPARATOR . $file, $filename );
                                        if ( is_file( $path ) ) {
                                            @unlink( $path );
                                        }
                                    }
                                    @unlink( $filename );
                                }

                                $seen[ ] = $index;
                            }
                        }
                        elseif ( is_dir( $filename ) ) {
                            if ( !in_array( $file, $omit ) ) {
                                $this->checkImageFilesForCorrespondingEntity( $filename, $seen );
                            }
                        }
                    }
                }
                closedir( $dir );
            }
        }
    }

    /**
     * @param null $filename
     *
     * @return mixed
     */
    public function locateImageEntityByFilename( $filename = NULL )
    {
        $repo  = $this->getRepo( 'CoreSysMediaBundle:Image' );
        $image = $repo->findOneByFilename( $filename );

        return $image;
    }
}