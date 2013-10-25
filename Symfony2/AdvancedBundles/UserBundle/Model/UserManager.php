<?php

/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Model;

use CoreSys\SiteBundle\Model\BaseManager;

/**
 * Class UserManager
 * @package CoreSys\UserBundle\Model
 */
class UserManager extends BaseManager
{

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return int
     */
    public function getUsersCountBetween( \DateTime $from, \DateTime $to )
    {
        $repo  = $this->getRepo( 'CoreSysUserBundle:User' );
        $count = $repo->getUsersCountBetween( $from, $to );

        return intval( $count );
    }

    /**
     * @param \DateTime $date
     *
     * @return int
     */
    public function getUsersCountForMonth( \DateTime $date )
    {
        $from = new \DateTime();
        $to   = new \DateTime();

        $month = $date->format( 'm' );
        $year  = $date->format( 'y' );

        $from->setTimestamp( mktime( 0, 0, 0, $month, 1, $year ) );
        $to->setTimestamp( mktime( 23, 59, 59, $month + 1, -1, $year ) );

        return $this->getUsersCountBetween( $from, $to );
    }

    /**
     * @param \DateTime $date
     *
     * @return int
     */
    public function getUsersCountForYear( \DateTime $date )
    {
        $from = new \DateTime();
        $to   = new \DateTime();
        $year = $date->format( 'y' );

        $from->setTimestamp( mktime( 0, 0, 0, 1, 1, $year ) );
        $to->setTimestamp( mktime( 23, 59, 59, 2, -1, $year ) );

        return $this->getUsersCountBetween( $from, $to );
    }
}