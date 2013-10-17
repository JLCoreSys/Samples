<?php

namespace CoreSys\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Imagine\Gd\Imagine;
use Imagine\Gd\Image as IImage;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;

use CoreSys\MediaBundle\Model\GifCreator;
use CoreSys\MediaBundle\Model\GifFrameExtractor;
use CoreSys\MediaBundle\Model\ImageWorkshop\ImageWorkshop;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="CoreSys\MediaBundle\Entity\ImageRepository")
 */
class Image
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    private $views;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="ratings_count", type="integer", nullable=true)
     */
    private $ratings_count;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var boolean
     *
     * @ORM\Column(name="png", type="boolean", nullable=true)
     */
    private $png;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gif", type="boolean", nullable=true)
     */
    private $gif;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_png", type="boolean", nullable=true)
     */
    private $allow_png;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_gif", type="boolean", nullable=true)
     */
    private $allow_gif;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="apply_watermark", type="boolean", nullable=true)
     */
    private $apply_watermark;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32, nullable=true)
     */
    private $type;

    /**
     * @var mixed
     */
    private $file;

    /**
     * @var array
     *
     * @ORM\Column(name="sizes", type="array", nullable=true)
     */
    private $sizes;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", nullable=true)
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * Construct a new instance of the Image Object
     */
    public function __construct()
    {
        $this->setActive( TRUE );
        $this->setAllowGif( FALSE );
        $this->setAllowPng( FALSE );
        $this->setApplyWatermark( TRUE );
        $this->setCreatedAt( new \DateTime() );
        $this->setFilename( NULL );
        $this->setGif( FALSE );
        $this->setPng( FALSE );
        $this->setPosition( 0 );
        $this->setRating( 3 );
        $this->setRatingsCount( 1 );
        $this->setTitle( NULL );
        $this->setType( 'pin' );
        $this->setViews( 0 );
    }

    /**
     * Get the file
     *
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * set the file
     *
     * @param null $file
     *
     * @return $this
     */
    public function setFile( $file = NULL )
    {
        $this->file = $file;

        return $this;
    }

    /**
     * get the file type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     *
     * @return $this
     */
    public function setType( $type = NULL )
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Image
     */
    public function setTitle( $title = NULL )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return intval( $this->views );
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return Image
     */
    public function setViews( $views = NULL )
    {
        $this->views = intval( $views );

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return floatval( $this->rating );
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Image
     */
    public function setRating( $rating = 0 )
    {
        $this->rating = floatval( $rating );

        return $this;
    }

    /**
     * Get ratings_count
     *
     * @return integer
     */
    public function getRatingsCount()
    {
        return intval( $this->ratings_count );
    }

    /**
     * Set ratings_count
     *
     * @param integer $ratingsCount
     *
     * @return Image
     */
    public function setRatingsCount( $ratingsCount = 0 )
    {
        $this->ratings_count = intval( $ratingsCount );

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active === TRUE;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Image
     */
    public function setActive( $active = TRUE )
    {
        $this->active = $active === TRUE;

        return $this;
    }

    /**
     * Get png
     *
     * @return boolean
     */
    public function getPng()
    {
        return $this->png === TRUE;
    }

    /**
     * Set png
     *
     * @param boolean $png
     *
     * @return Image
     */
    public function setPng( $png = FALSE )
    {
        $this->png = $png;

        return $this;
    }

    /**
     * Get gif
     *
     * @return boolean
     */
    public function getGif()
    {
        return $this->gif === TRUE;
    }

    /**
     * Set gif
     *
     * @param boolean $gif
     *
     * @return Image
     */
    public function setGif( $gif = FALSE )
    {
        $this->gif = $gif;

        return $this;
    }

    /**
     * Get allow_png
     *
     * @return boolean
     */
    public function getAllowPng()
    {
        return $this->allow_png === TRUE;
    }

    /**
     * Set allow_png
     *
     * @param boolean $allowPng
     *
     * @return Image
     */
    public function setAllowPng( $allowPng = FALSE )
    {
        $this->allow_png = $allowPng === TRUE;

        return $this;
    }

    /**
     * Get allow_gif
     *
     * @return boolean
     */
    public function getAllowGif()
    {
        return $this->allow_gif === TRUE;
    }

    /**
     * Set allow_gif
     *
     * @param boolean $allowGif
     *
     * @return Image
     */
    public function setAllowGif( $allowGif = FALSE )
    {
        $this->allow_gif = $allowGif === TRUE;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return intval( $this->position );
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Image
     */
    public function setPosition( $position = 0 )
    {
        $this->position = intval( $position );

        return $this;
    }

    /**
     * Get apply_watermark
     *
     * @return boolean
     */
    public function getApplyWatermark()
    {
        return $this->apply_watermark;
    }

    /**
     * Set apply_watermark
     *
     * @param boolean $applyWatermark
     *
     * @return Image
     */
    public function setApplyWatermark( $applyWatermark = FALSE )
    {
        $this->apply_watermark = $applyWatermark === TRUE;

        return $this;
    }

    /**
     * @param bool $manual
     *
     * @return bool
     */
    public function uploadImage( $manual = FALSE, $crop = FALSE, $crop_width = NULL, $crop_height = NULL )
    {
        if ( empty( $this->file ) ) {
            return FALSE;
        }

        $upload_folder = $this->getTempFolder();
        $image_folder  = $this->getDatedFolder();
        $original_name = $this->file->getFileName();

        if ( !$manual ) {
            $this->file->move( $upload_folder, $original_name );
        }

        $ext = pathinfo( $original_name, PATHINFO_EXTENSION );
        $ext = strtolower( $ext );
        if ( $ext != 'png' && $ext != 'gif' ) {
            $ext = 'jpg';
        }

        if ( $ext == 'gif' ) {
            $this->setGif( TRUE );
            $this->setAllowGif( TRUE );
        }

        $filename = str_replace( '.', '', microtime( TRUE ) ) . '.' . $ext;
        $this->setFilename( $filename );

        $src = $upload_folder . DIRECTORY_SEPARATOR . $original_name;
        $dst = $image_folder . DIRECTORY_SEPARATOR . $filename;

        $image_size = getimagesize( $src );
        $width      = $image_size[ 0 ];
        $height     = $image_size[ 1 ];

        if ( $crop && !empty( $crop_width ) && !empty( $crop_height ) ) {
            $width  = $crop_width;
            $height = $crop_height;
        }

        $this->setWidth( $width );
        $this->setHeight( $height );

        if ( $this->getGif() ) {
            $result = $this->uploadAndCropGifImage( $src, $dst, $width, $height );
        }
        else {
            $result = FALSE;
        }

        if ( !$result ) {
            // use normal methods to upload this image
            $imagine = new Imagine();

            $size = new Box( $width, $height );

            $imagine->open( $src )->resize( $size )->save( $dst );
        }

        @unlink( $src );

        $res = $this->createSizes();
        $this->cleanupUploadsFolder();

        return $res;
    }

    /**
     * Clean up the uploads folder and remove any
     * unnecessary files
     */
    public function cleanupUploadsFolder()
    {
        $upload_folder = $this->getTempFolder();
        if ( is_dir( $upload_folder ) ) {
            $this->cleanupFolder( $upload_folder );
        }
    }

    /**
     * Cleanup a specific folder
     *
     * @param     $folder
     * @param int $time
     */
    public function cleanupFolder( $folder, $time = 300 )
    {
        $diff = time() - $time;
        if ( is_dir( $folder ) ) {
            $dir = opendir( $folder );
            if ( $dir ) {
                while ( FALSE !== ( $file = readdir( $dir ) ) ) {
                    if ( $file != '.' && $file != '..' ) {
                        $filename = $folder . DIRECTORY_SEPARATOR . $file;
                        if ( is_dir( $filename ) ) {
                            $this->cleanupFolder( $folder, $time );
                        }
                        else if ( is_file( $filename ) ) {
                            $mtime = filemtime( $filename );
                            if ( $mtime <= $diff ) {
                                @unlink( $filename );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Upload handler for gif images
     *
     * @param $src
     * @param $dst
     * @param $width
     * @param $height
     *
     * @return bool
     */
    public function uploadAndCropGifImage( $src, $dst, $width, $height )
    {
        $src_size   = getimagesize( $src );
        $src_width  = $src_size[ 0 ];
        $src_height = $src_size[ 1 ];

        if ( $src_width == $width && $src_height == $height ) {
            // we do not need to edit this file at all
            @copy( $src, $dst );

            return TRUE;
        }

        $offset_x = $offset_y = 0;
        if ( $src_width != $width ) {
            $offset_x = intval( ( $src_width - $width ) / 2 );
        }
        if ( $src_height != $height ) {
            $offset_y = intval( ( $src_height - $height ) / 2 );
        }

        if ( GifFrameExtractor::isAnimatedGif( $src ) ) {
            $gfe              = new GifFrameExtractor();
            $frames           = $gfe->extract( $src );
            $retouched_frames = array();
            foreach ( $frames as $frame ) {
                $frame_layer = ImageWorkshop::initFromResourceVar( $frame[ 'image' ] );
                $frame_layer->cropInPixel( $width, $height, $offset_x, $offset_y );
                $retouched_frames[ ] = $frame_layer->getResult();
            }

            $gc = new GifCreator();
            $gc->create( $retouched_frames, $gfe->getFrameDurations(), 0 );

            file_put_contents( $dst, $gc->getGif() );

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Resize a gif image, done separately in the event of
     * an animated gif
     *
     * @param $src
     * @param $dst
     * @param $width
     * @param $height
     *
     * @return bool
     */
    public function resizeGifImage( $src, $dst, $width, $height )
    {
        $src_size   = getimagesize( $src );
        $src_width  = $src_size[ 0 ];
        $src_height = $src_size[ 1 ];

        if ( $src_width == $width && $src_height == $height ) {
            // we do not need to edit this file at all
            @copy( $src, $dst );

            return TRUE;
        }

        $offset_x = $offset_y = 0;
        if ( $src_width != $width ) {
            $offset_x = intval( ( $src_width - $width ) / 2 );
        }
        if ( $src_height != $height ) {
            $offset_y = intval( ( $src_height - $height ) / 2 );
        }

        if ( GifFrameExtractor::isAnimatedGif( $src ) ) {
            $gfe              = new GifFrameExtractor();
            $frames           = $gfe->extract( $src );
            $retouched_frames = array();
            foreach ( $frames as $frame ) {
                $frame_layer = ImageWorkshop::initFromResourceVar( $frame[ 'image' ] );
                $frame_layer->resizeInPixel( $width, $height, TRUE );
                $retouched_frames[ ] = $frame_layer->getResult();
            }

            $gc = new GifCreator();
            $gc->create( $retouched_frames, $gfe->getFrameDurations(), 0 );

            file_put_contents( $dst, $gc->getGif() );

            return TRUE;
        }
        else {
            // we can simply cut this gif down the regular way
            // use normal methods to upload this image
            $imagine = new Imagine();

            $size = new Box( $width, $height );
            $mode = ImageInterface::THUMBNAIL_OUTBOUND;

            $imagine->open( $src )->thumbnail( $size, $mode )->save( $dst );
        }

        return FALSE;
    }

    /**
     * @return null
     */
    public function getTempFolder()
    {
        $folder = $this->getPhotosFolder() . DIRECTORY_SEPARATOR . 'tmp';

        return $this->verifyFolder( $folder );
    }

    /**
     * @return bool
     */
    public function createSizes()
    {
        $sizes  = $this->getSizes();
        $sizes  = array_reverse( $sizes );
        $src    = $this->getSizeFile( 'original' );
        $width  = $this->getWidth();
        $height = $this->getHeight();
        $ratio  = $height / $width;

        $imagine = new Imagine();
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;

        $ext      = pathinfo( $src, PATHINFO_EXTENSION );
        $filename = $this->getFilename();

        foreach ( $sizes as $size => $dst_width ) {
            if ( $size != 'orig' && $size != 'original' ) {
                if ( $dst_width <= $width ) {
                    $dst        = $this->getSizeFolder( $size ) . DIRECTORY_SEPARATOR . $filename;
                    $dst_height = intval( $dst_width * $ratio );
                    if ( $this->getGif() ) {
                        $this->resizeGifImage( $src, $dst, $dst_width, $dst_height );
                    }
                    else {
                        $dst_size = new Box( $dst_width, $dst_height );
                        $imagine->open( $src )->resize( $dst_size )->save( $dst );
                    }

                    if ( is_file( $dst ) ) {
                        $src = $dst;
                    }
                }
            }
        }

        return TRUE;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth( $width = 0 )
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight( $height = 0 )
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultImage()
    {
        $image = $this->getPhotosFolder() . DIRECTORY_SEPARATOR . 'default.jpg';

        return $image;
    }

    /**
     * @param null $size
     *
     * @return bool
     */
    public function show( $size = NULL )
    {
        $out = NULL;
        if ( empty( $size ) ) {
            $size = 'original';
        }

        $size = strtolower( trim( $size ) );
        if ( !$this->hasSize( $size ) ) {
            switch ( $size ) {
                case 'original':
                    return $this->show( 'large' );
                    break;
                case 'large':
                    return $this->show( 'medium' );
                    break;
                case 'medium':
                    return $this->show( 'small' );
                    break;
                case 'small':
                    return $this->show( 'thumb' );
                    break;
                case 'thumb':
                    return $this->show( 'tiny' );
                    break;
                case 'tiny':
                default:
                    echo 'File not found.';

                    return FALSE;
                    break;
            }
        }

        $file = $this->getSizeFile( $size );
        if ( !is_file( $file ) ) {
            echo 'File not found.';

            return FALSE;
        }

        $imagine = new Imagine();
        $image   = $imagine->open( $file );
        $image->show( 'jpg' );

        return TRUE;
    }

    /**
     * @param null $size
     *
     * @return bool
     */
    public function hasSize( $size = NULL )
    {
        $file = $this->getSizeFile( $size );

        return is_file( $file );
    }

    /**
     * @param null $size
     *
     * @return null|string
     */
    public function getSizeFile( $size = NULL )
    {
        $size = strtolower( trim( $size ) );
        if ( $size == 'original' || $size == 'orig' ) {
            $file = $this->getDatedFolder() . DIRECTORY_SEPARATOR . $this->getFilename();

            return $file;
        }

        $valid = $this->getSizes( FALSE );
        if ( !in_array( $size, $valid ) ) {
            return NULL;
        }

        $file = $this->getSizeFolder( $size ) . DIRECTORY_SEPARATOR . $this->getFilename();

        return $file;
    }

    /**
     * @return null
     */
    public function getDatedFolder()
    {
        $date    = $this->getCreatedAt();
        $year    = $date->format( 'Y' );
        $month   = $date->format( 'm' );
        $day     = $date->format( 'd' );
        $hour    = $date->format( 'H' );
        $base    = $this->getPhotosFolder();
        $folders = array( $year, $month, $day, $hour );
        $folder  = $base;
        foreach ( $folders as $f ) {
            $folder .= DIRECTORY_SEPARATOR . $f;
            $folder = $this->verifyFolder( $folder );
        }

        return $folder;
    }

    /**
     * @return null
     */
    public function getPhotosFolder()
    {
        $folder = $this->getMediaFilesFolder() . DIRECTORY_SEPARATOR . 'photos';

        return $this->verifyFolder( $folder );
    }

    /**
     * @return null
     */
    public function getMediaFilesFolder()
    {
        $folder = $this->getBaseFolder() . DIRECTORY_SEPARATOR . 'media_files';

        return $this->verifyFolder( $folder );
    }

    /**
     * @param null $size
     *
     * @return null
     */
    public function getSizeFolder( $size = NULL )
    {
        $folder = $this->getDatedFolder();
        if ( empty( $size ) ) {
            return $folder;
        }

        $size  = strtolower( trim( $size ) );
        $valid = $this->getSizes( FALSE );
        if ( !in_array( $size, $valid ) ) {
            return $folder;
        }

        $folder .= DIRECTORY_SEPARATOR . $size;

        return $this->verifyFolder( $folder );
    }

    /**
     * @param null $sub_folder
     */
    public function moveImagesToPublic( $sub_folder = NULL )
    {
        $sizes = $this->getSizes();
        foreach ( $sizes as $key => $val ) {
            echo '<br>' . $key . ' = ' . $val;
        }
        exit;
    }

    /**
     * @param bool $full
     *
     * @return array
     */
    public function getSizes( $full = TRUE )
    {
        $sizes = $this->sizes;
        if ( empty( $sizes ) ) {
            // return the default set of sizes
            if ( !$full ) {
                return array( 'teenie', 'tiny', 'thumb', 'small', 'medium', 'large', 'xlarge', 'xxlarge', 'xxxlarge' );
            }

            return array(
                'teenie'   => 50,
                'tiny'     => 75,
                'thumb'    => 150,
                'small'    => 240,
                'medium'   => 800,
                'large'    => 1024,
                'xlarge'   => 1280,
                'xxlarge'  => 1600,
                'xxxlarge' => 2400
            );

        }
        else {
            // return the pre-set list of sizes
            $return = array();
            if ( !$full ) {
                foreach ( $sizes as $key => $width ) {
                    $return[ ] = $key;
                }
            }
            else {
                $return = $sizes;
            }

            return $return;
        }
    }

    /**
     * @param null $sizes
     *
     * @return $this
     */
    public function setSizes( $sizes = NULL )
    {
        $sizes = is_array( $sizes ) ? $sizes : array();
        if ( empty( $sizes ) || count( $sizes ) == 0 ) {
            $this->sizes = NULL;
        }
        else {
            $this->sizes = $sizes;
        }

        return $this;
    }

    /**
     * Does this image have a public version of the files?
     * @return boolean
     */
    public function hasPublicImages( $sub_folder = NULL, $create = TRUE )
    {
        $sizes    = $this->getSizes( FALSE );
        $sizes[ ] = 'original';

        $expires = time() - ( 60 * 60 * 24 * 7 );

        $return = FALSE;

        foreach ( $sizes as $size ) {
            $private = $this->getSizeFile( $size );
            $public  = $this->getPublicSizeFile( $sub_folder, $size );

            $has_private = is_file( $private );
            $has_public  = is_file( $public );

            // check expiration
            if ( $has_public ) {
                $mtime = filemtime( $public );
                if ( $mtime <= $expires ) {
                    // the image is older than 7 days, remove it
                    // we will re-create it when needed
                    @unlink( $public );
                }
                $has_public = is_file( $public );
            }

            if ( $create ) {
                // this will move the private image to the location of the public image
                if ( $has_private && !$has_public ) {
                    copy( $private, $public );
                    $has_public = is_file( $public );
                }
            }

            if ( $has_public ) {
                $return = TRUE;
            }
        }

        return $return;
    }

    /**
     * @param null $sub_folder
     * @param null $size
     * @param bool $next
     *
     * @return mixed|string
     */
    public function getUrl( $sub_folder = NULL, $size = NULL, $next = TRUE )
    {
        $size = strtolower( trim( $size ) );
        if ( empty( $size ) || $size == 'orig' || $size == 'orignal' ) {
            $size = 'original';
        }

        $file = $this->getPublicSizeFile( $sub_folder, $size, $next );
        $web  = $this->getWebFolder();

        $url = str_replace( $web, '', $file );
        $url = substr( $url, 0, 1 ) == DIRECTORY_SEPARATOR ? substr( $url, 1 ) : $url;
        if ( DIRECTORY_SEPARATOR != '/' ) {
            $url = str_replace( DIRECTORY_SEPARATOR, '/', $url );
        }

        return $url;
    }

    /**
     * @param null $sub_folder
     * @param null $size
     *
     * @return null|string
     */
    public function getPublicSizeFile( $sub_folder = NULL, $size = NULL, $next = FALSE )
    {
        $size = strtolower( trim( $size ) );
        if ( $size == 'original' || $size == 'orig' ) {
            $file = $this->getPublicImagesFolder( $sub_folder ) . DIRECTORY_SEPARATOR . $this->getFilename();

            return $file;
        }

        $valid = $this->getSizes( FALSE );
        if ( !in_array( $size, $valid ) ) {
            return NULL;
        }

        $file = $this->getPublicSizeFolder( $sub_folder, $size ) . DIRECTORY_SEPARATOR . $this->getFilename();
        if ( $next ) {
            if ( !is_file( $file ) ) {
                switch ( $size ) {
                    case 'orginal':
                        $file = $this->getPublicSizeFile( $sub_folder, 'large', TRUE );
                        break;
                    case 'large':
                        $file = $this->getPublicSizeFile( $sub_folder, 'medium', TRUE );
                        break;
                    case 'medium':
                        $file = $this->getPublicSizeFile( $sub_folder, 'small', TRUE );
                        break;
                    case 'small':
                        $file = $this->getPublicSizeFile( $sub_folder, 'thumb', TRUE );
                        break;
                    case 'thumb':
                        $file = $this->getPublicSizeFile( $sub_folder, 'tiny', TRUE );
                        break;
                    case 'tiny':
                        $file = NULL;
                        break;
                }
            }
        }

        return $file;
    }

    /**
     * @param null $sub_folder
     *
     * @return string
     */
    public function getPublicImagesFolder( $sub_folder = NULL )
    {
        if ( empty( $sub_folder ) ) {
            return 'Must specify a sub folder.';
        }

        $img_folder = $this->getWebImagesFolder( $sub_folder );

        $date = $this->getCreatedAt( 'Ymd' );
        $date = md5( $date );

        $folders = array( $date );
        foreach ( $folders as $part ) {
            $img_folder .= DIRECTORY_SEPARATOR . $part;
            if ( !is_dir( $img_folder ) ) {
                @mkdir( $img_folder, 0777 );
                @chmod( $img_folder, 0777 );
            }
        }

        return $img_folder;
    }

    /**
     * @return string
     */
    public function getWebFolder()
    {
        $folder = $this->getBaseFolder() . DIRECTORY_SEPARATOR . 'web';

        return $folder;
    }

    /**
     * @return string
     */
    public function getBaseFolder()
    {
        $base = dirname( __FILE__ );
        $web  = $base . DIRECTORY_SEPARATOR . 'web';
        while ( !is_dir( $web ) ) {
            $base = dirname( $base );
            $web  = $base . DIRECTORY_SEPARATOR . 'web';
        }

        return $base;
    }

    /**
     * @param null $folder
     *
     * @return null
     */
    public function verifyFolder( $folder = NULL )
    {
        if ( !empty( $folder ) ) {
            if ( !is_dir( $folder ) ) {
                @mkdir( $folder, 0777 );
                @chmod( $folder, 0777 );
            }
        }

        return $folder;
    }

    /**
     * Get created_at
     *
     * @param string $format
     *
     * @return \DateTime
     */
    public function getCreatedAt( $format = NULL )
    {
        if ( !empty( $format ) ) {
            return $this->created_at->format( $format );
        }

        return $this->created_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     *
     * @return Image
     */
    public function setCreatedAt( $createdAt )
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename( $filename )
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param null $sub_folder
     * @param null $size
     *
     * @return null|string
     */
    public function getPublicSizeFolder( $sub_folder = NULL, $size = NULL )
    {
        $folder = $this->getPublicImagesFolder( $sub_folder );
        if ( empty( $size ) ) {
            return $folder;
        }

        $size  = strtolower( trim( $size ) );
        $valid = $this->getSizes( FALSE );
        if ( !in_array( $size, $valid ) ) {
            return $folder;
        }

        $folder .= DIRECTORY_SEPARATOR . $size;

        return $this->verifyFolder( $folder );
    }

    /**
     * @param string $sub_folder
     */
    public function remove( $sub_folder = 'ads' )
    {
        $this->preremove( $sub_folder );
    }

    /**
     * PreRemove
     *
     * @ORM\PreRemove
     */
    public function preremove( $sub_folder )
    {
        $sizes    = $this->getSizes( FALSE );
        $sizes[ ] = 'original';

        foreach ( $sizes as $size ) {
            $private = $this->getSizeFile( $size );
            $public  = $this->getPublicSizeFile( $sub_folder, $size );
            if ( is_file( $private ) ) {
                unlink( $private );
            }
            if ( is_file( $public ) ) {
                unlink( $public );
            }
        }
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        $file = $this->getSizeFile( 'original' );
        if ( is_file( $file ) ) {
            $size = filesize( $file );

            return $size;
        }

        return 0;
    }

    /**
     * @param null $sub_folder
     *
     * @return string
     */
    private function getWebImagesFolder( $sub_folder = NULL )
    {
        $folder = $this->getWebFolder() . DIRECTORY_SEPARATOR . 'images'
                  . DIRECTORY_SEPARATOR . $sub_folder;
        if ( !is_dir( $folder ) ) {
            return $this->createWebImagesFolder( $sub_folder );
        }

        return $folder;
    }

    /**
     * @param null $sub_folder
     *
     * @return string
     */
    private function createWebImagesFolder( $sub_folder = NULL )
    {
        $folder = $this->getWebFolder() . DIRECTORY_SEPARATOR . 'images';
        if ( !is_dir( $folder ) ) {
            $folder = $this->verifyFolder( $folder );
        }

        $folder .= DIRECTORY_SEPARATOR . $sub_folder;
        if ( is_dir( $folder ) ) {
            return $folder;
        }

        @mkdir( $folder, 0777 );
        @chmod( $folder, 0777 );

        $file = $folder . DIRECTORY_SEPARATOR . '.htaccess';
        if ( !is_file( $file ) ) {
            $fp = fopen( $file, 'w+' );
            if ( $fp ) {
                fwrite( $fp, 'Options -Indexes' );
                fclose( $fp );
            }
        }

        return $folder;
    }
}
