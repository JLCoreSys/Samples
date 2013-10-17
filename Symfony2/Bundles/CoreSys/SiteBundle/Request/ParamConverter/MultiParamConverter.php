<?php

namespace CoreSys\SiteBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Mapping\MappingException;

class MultiParamConverter implements ParamConverterInterface
{

    protected $registry;

    public function __construct( Registry $registry = NULL )
    {
        if ( is_null( $registry ) ) {
            return;
        }

        $this->registry = $registry;
    }

    public function apply( Request $request, ConfigurationInterface $configuration )
    {
        $class   = $configuration->getClass();
        $options = $this->getOptions( $configuration );

        // find by identifier?
        if ( FALSE === $object = $this->find( $request, $configuration, $options ) ) {
            // find by criteria
            if ( FALSE === $object = $this->findOneBy( $class, $request, $options ) ) {
                throw new \LogicException( 'Unable to guess how to get a Doctrine instance from the request information.' );
            }
        }

        if ( NULL === $object && FALSE === $configuration->isOptional() ) {
            throw new NotFoundHttpException( sprintf( '%s object not found.', $class ) );
        }

        $request->attributes->set( $configuration->getName(), $object );
    }

    protected function find( Request $request, ConfigurationInterface $configuration, $options )
    {
        $class = $configuration->getClass();

        $froms    = array();
        $froms[ ] = $configuration->getFrom();
        if ( isset( $options[ 'id' ] ) ) $froms[ ] = $options[ 'id' ];
        $froms[ ] = $configuration->getName() . "_id";
        $froms[ ] = "id";

        $from = NULL;
        foreach ( $froms as $from_entry ) {
            if ( $request->attributes->has( $from_entry ) ) {
                $from = $from_entry;
                break;
            }
        }

        if ( !$from ) {
            return FALSE;
        }

        $method = isset( $options[ 'method' ] ) ? $options[ 'method' ] : "find";

        return $this->registry->getRepository( $class, $options[ 'entity_manager' ] )->$method( $request->attributes->get( $from ) );
    }

    protected function findOneBy( $class, Request $request, $options )
    {
        $criteria = array();
        $metadata = $this->registry->getEntityManager( $options[ 'entity_manager' ] )->getClassMetadata( $class );
        foreach ( $request->attributes->all() as $key => $value ) {
            if ( $metadata->hasField( $key ) ) {
                $criteria[ $key ] = $value;
            }
        }

        if ( !$criteria ) {
            return FALSE;
        }

        return $this->registry->getRepository( $class, $options[ 'entity_manager' ] )->findOneBy( $criteria );
    }

    public function supports( ConfigurationInterface $configuration )
    {
        if ( NULL === $this->registry ) {
            return FALSE;
        }

        if ( NULL === $configuration->getClass() ) {
            return FALSE;
        }

        $options = $this->getOptions( $configuration );

        // Doctrine Entity?
        try {
            $this->registry->getEntityManager( $options[ 'entity_manager' ] )->getClassMetadata( $configuration->getClass() );

            return TRUE;
        } catch ( MappingException $e ) {
            return FALSE;
        }
    }

    protected function getOptions( ConfigurationInterface $configuration )
    {
        return array_replace( array(
                                  'entity_manager' => 'default',
                              ), $configuration->getOptions() );
    }
}