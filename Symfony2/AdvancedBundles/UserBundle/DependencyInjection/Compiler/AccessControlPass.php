<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AccessControlPass
 * @package CoreSys\UserBundle\DependencyInjection\Compiler
 */
class AccessControlPass implements CompilerPassInterface
{

    /**
     * @var
     */
    private $app_folder;

    /**
     * @var
     */
    private $config_folder;

    /**
     * @var
     */
    private $tcontainer;

    /**
     * @param ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
        if ( !$container->hasDefinition( 'security.access_map' ) ) {
            echo 'No Access_Map';
            exit;
        }

        $this->tcontainer    = $container;
        $accessMapDefinition = $container->getDefinition( 'security.access_map' );
        $roleDefinition      = $container->getParameter( 'security.role_hierarchy.roles' );

        $role_yml   = Yaml::parse( $this->getRolesFileContents() );
        $access_yml = Yaml::parse( $this->getAccessControlFileContents() );

        /**
         * Fix the role yml
         * Each entry MUST be an array, in config, if a single role is added, it
         * defaults to a string, we must convert this back to an array
         */
        foreach ( $role_yml as $index => $data ) {
            if ( !is_array( $data ) ) {
                $role_yml[ $index ] = array( $data );
            }
        }

        $container->setParameter( 'security.role_hierarchy.roles', $role_yml );

        // add custom mapping information here
        $contents = $this->getAccessControlFileContents();

        foreach ( $access_yml as $idx => $access ) {
            $path             = isset( $access[ 'path' ] ) ? $access[ 'path' ] : NULL;
            $ip               = isset( $access[ 'ip' ] ) ? $access[ 'ip' ] : NULL;
            $roles            = isset( $access[ 'roles' ] ) ? $access[ 'roles' ] : NULL;
            $host             = isset( $access[ 'host' ] ) ? $access[ 'host' ] : NULL;
            $methods          = array();
            $requires_channel = isset( $access[ 'channel' ] ) ? $access[ 'channel' ] : NULL;
            $matcher          = $this->createRequestMatcher( $path, $host, $methods, $ip );

            $accessMapDefinition->addMethodCall( 'add', array(
                $matcher, $roles, $requires_channel
            ) );
        }
    }

    /**
     * @return string
     */
    public function locateAppFolder()
    {
        if ( !empty( $this->app_folder ) ) {
            return $this->app_folder;
        }

        $base   = dirname( __FILE__ );
        $folder = $base . DIRECTORY_SEPARATOR . 'app';
        while ( !is_dir( $folder ) ) {
            $base   = dirname( $base );
            $folder = $base . DIRECTORY_SEPARATOR . 'app';
        }
        $this->app_folder = $folder;

        return $this->app_folder;
    }

    /**
     * @return string
     */
    public function getConfigFolder()
    {
        if ( !empty( $this->config_folder ) ) {
            return $this->config_folder;
        }
        $folder              = $this->locateAppFolder() . DIRECTORY_SEPARATOR . 'config';
        $this->config_folder = $folder;

        return $this->config_folder;
    }

    /**
     * @return null|string
     */
    public function getRolesFileContents()
    {
        $file = $this->getConfigFolder() . DIRECTORY_SEPARATOR . 'roles.yml';
        if ( is_file( $file ) ) {
            $contents = file_get_contents( $file );
        }
        else {
            $contents = NULL;
        }

        return $contents;
    }

    /**
     * @return null|string
     */
    public function getAccessControlFileContents()
    {
        $file = $this->getConfigFolder() . DIRECTORY_SEPARATOR . 'access_control.yml';

        if ( is_file( $file ) ) {
            $contents = file_get_contents( $file );
        }
        else {
            $contents = NULL;
        }

        return $contents;
    }

    /**
     * @param null  $path
     * @param null  $host
     * @param array $methods
     * @param null  $ip
     * @param array $attributes
     *
     * @return Reference
     */
    public function createRequestMatcher( $path = NULL, $host = NULL, $methods = array(), $ip = NULL, array $attributes = array() )
    {
        $serialized = serialize( array( $path, $host, $methods, $ip, $attributes ) );
        $id         = 'security.request_matcher.' . md5( $serialized ) . sha1( $serialized );

        if ( $methods ) {
            $methods = array_map( 'strtoupper', (array)$methods );
        }

        $arguments = array( $path, $host, $methods, $ip, $attributes );
        while ( count( $arguments ) > 0 && !end( $arguments ) ) {
            array_pop( $arguments );
        }

        var_dump( $arguments );

        $this->tcontainer->register( $id, '%security.matcher.class%' )
                         ->setPublic( FALSE )
                         ->setArguments( $arguments );

        return new Reference( $id );
    }
}