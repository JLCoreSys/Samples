<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Twig;

use CoreSys\SiteBundle\Twig\BaseExtension;

/**
 * Common user extensions to be used in conjunction with
 * the CoreSys Bundles
 *
 * Class UserExtension
 * @package CoreSys\SiteBundle\Twig
 */
class UserExtension extends BaseExtension
{

    /**
     * @var string
     */
    protected $name = 'user_extension';

    /**
     * @return array
     */
    public function getFilters()
    {
        return array();
    }

    /**
     * @return \CoreSys\SiteBundle\Twig\repo|\Doctrine\ORM\EntityRepository|null
     */
    public function getUserRepo()
    {
        return $this->getRepo( 'CoreSysUserBundle:User' );
    }

    /**
     * @return \CoreSys\SiteBundle\Twig\repo|\Doctrine\ORM\EntityRepository|null
     */
    public function getRoleRepo()
    {
        return $this->getRepo( 'CoreSysUserBundle:Role' );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'userCount'          => new \Twig_Function_Method( $this, 'getUsersCount' ),
            'userMonthCount'     => new \Twig_Function_Method( $this, 'getUserCountByMonth' ),
            'userLastYearCounts' => new \Twig_Function_Method( $this, 'getUserLastYearCounts' )
        );
    }

    /**
     * @param null $active
     *
     * @return mixed
     */
    public function getUsersCount( $active = NULL )
    {
        $repo = $this->getUserRepo();

        return $repo->getUsersCount( $active );
    }

    /**
     * @param string $delim
     *
     * @return string
     */
    public function getUserLastYearCounts( $delim = ',' )
    {
        $return = array();
        for ( $i = 11; $i >= 0; $i-- ) {
            $month     = date( 'm' ) - $i;
            $count     = $this->getUserCountByMonth( $month );
            $return[ ] = $count;
        }

        return implode( $delim, $return );
    }

    /**
     * get the user count for a specific month
     *
     * @param int month
     * @param int year
     *
     * @retrun int
     */
    public function getUserCountByMonth( $month = NULL, $year = NULL )
    {
        if ( empty( $month ) ) $month = date( 'm' );
        if ( empty( $year ) ) $year = date( 'y' );

        $manager = $this->get( 'core_sys_user.user_manager' );
        $date    = new \DateTime();
        $date->setTimestamp( mktime( 0, 1, 1, $month, 1, $year ) );

        return $manager->getUsersCountForMonth( $date );
    }
}