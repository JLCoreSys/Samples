<?php

namespace CoreSys\SiteBundle\Model;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class BaseManager
 * @package CoreSys\SiteBundle\Model
 */
class BaseManager
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entity_manager;

    /**
     * @var
     */
    private $output;

    /**
     * @var bool
     */
    private $log;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @var
     */
    private $upload_folder;

    /**
     * @param EntityManager $em
     * @param Container     $container
     */
    public function __construct( EntityManager $em, Container $container )
    {
        $this->entity_manager = $em;
        $this->log            = FALSE;
        $this->container      = $container;
    }

    /**
     * @param $name
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepo( $name )
    {
        return $this->getEntityManager()->getRepository( $name );
    }

    /**
     * @param $entity
     */
    public function persist( $entity )
    {
        if ( is_object( $entity ) ) {
            if ( method_exists( $entity, 'setContainer' ) ) {
                $container = $this->container;
                $entity->setContainer( $container );
            }
            $functions = array( 'prepersist', 'prePersist', 'PrePersist' );
            foreach ( $functions as $function ) {
                if ( method_exists( $entity, $function ) ) {
                    $entity->$function();
                }
            }
        }
        $this->getEntityManager()->persist( $entity );

        return $this;
    }

    /**
     * @param       $entity
     * @param array $params
     */
    public function remove( $entity, $params = array() )
    {
        if ( is_object( $entity ) ) {
            if ( method_exists( $entity, 'setContainer' ) ) {
                $container = $this->container;
                $entity->setContainer( $container );
            }
            $functions = array( 'preremove', 'preRemove', 'PreRemove', 'predelete', 'preDelete', 'PreDelete', 'remove', 'delete' );
            foreach ( $functions as $function ) {
                if ( method_exists( $entity, $function ) ) {
                    call_user_func_array( array( $entity, $function ), $params );
                }
            }
        }
        $this->getEntityManager()->remove( $entity );

        return $this;
    }

    /**
     *
     */
    public function flush()
    {
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * @param $entity_manager
     */
    public function setEntityManager( $entity_manager )
    {
        $this->entity_manager = $entity_manager;

        return $this;
    }

    /**
     * @return bool
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param $log
     */
    public function setLog( $log )
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param $output
     */
    public function setOutput( $output )
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param $msg
     */
    public function log( $msg )
    {
        if ( !$this->log ) {
            return;
        }
        if ( is_array( $msg ) ) {
            $msg = implode( '<br>', $msg );
        }
        if ( !empty( $this->output ) ) {
            $msg = str_replace( '<br>', "\n", $msg );
            $this->output->writeln( $msg );
        }
        else {
            $msg = preg_replace( "/[\r\n]+/", '<br>', $msg );
            echo $msg . '<br>';
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaImagesFolder()
    {
        return $this->getMediaFilesFolder() . DIRECTORY_SEPARATOR . 'photos';
    }

    /**
     * @return string
     */
    public function getMediaFilesFolder()
    {
        return $this->getRootFolder() . DIRECTORY_SEPARATOR . 'media_files';
    }

    /**
     * @return string
     */
    public function getRootFolder()
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
     * @return string
     */
    public function getMediaVideosFolder()
    {
        return $this->getMediaFilesFolder() . DIRECTORY_SEPARATOR . 'videos';
    }

    /**
     * @return null
     */
    public function getUploadsFolder()
    {
        return $this->getUploadFolder();
    }

    /**
     * @return null
     */
    public function getUploadFolder()
    {
        if ( !empty( $this->upload_folder ) ) {
            return $this->upload_folder;
        }
        $folder              = $this->getWebFolder() . DIRECTORY_SEPARATOR . 'upload';
        $this->upload_folder = $this->verifyFolder( $folder );

        return $this->upload_folder;
    }

    /**
     * @return string
     */
    public function getWebFolder()
    {
        return $this->getRootFolder() . DIRECTORY_SEPARATOR . 'web';
    }

    /**
     * @param null $folder
     *
     * @return null
     */
    public function verifyFolder( $folder = NULL )
    {
        if ( !is_dir( $folder ) ) {
            @mkdir( $folder, 0777 );
            @chmod( $folder, 0777 );
        }

        return $folder;
    }

    /**
     * @param $name
     *
     * @return object
     */
    public function get( $name )
    {
        return $this->container->get( $name );
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param       $route
     * @param array $parameters
     * @param bool  $absolute
     *
     * @return mixed
     */
    public function generateUrl( $route, $parameters = array(), $absolute = FALSE )
    {
        return $this->getContainer()->get( 'router' )->generate( $route, $parameters, $absolute );
    }
}