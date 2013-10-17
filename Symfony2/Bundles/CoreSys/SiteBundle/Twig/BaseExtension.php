<?php

namespace CoreSys\SiteBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Doctrine\ORM\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class BaseExtension
 * @package CoreSys\SiteBundle\Twig
 */
class BaseExtension extends Twig_Extension
{

    /**
     * @var string
     */
    protected $name = 'base_extension';

    /**
     * @var EntityManager $entity_manager
     */
    private $entity_manager;

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var Session
     */
    private $session;

    /**
     * construct a new common extensions object
     *
     * @param EntityManager      $entity_manager
     * @param ContainerInterface $container
     * @param session            $session
     */
    public function __construct( EntityManager $entity_manager, ContainerInterface $container, Session $session )
    {
        $this->entity_manager = $entity_manager;
        $this->container      = $container;
        $this->session        = $session;
    }

    /**
     * get session
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * get the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * get the container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return parent::getFilters();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return parent::getFunctions();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     *
     * @return object
     */
    public function get( $name = NULL )
    {
        return $this->getContainer()->get( $name );
    }

    /**
     * @param null $name
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepo( $name = NULL )
    {
        return $this->getEntityManager()->getRepository( $name );
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