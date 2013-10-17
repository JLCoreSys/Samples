<?php

namespace CoreSys\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreSys\MediaBundle\Entity\Image;

/**
 * Slider
 *
 * @ORM\Table(name="slider")
 * @ORM\Entity(repositoryClass="CoreSys\SiteBundle\Entity\SliderRepository")
 */
class Slider
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
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var mixed
     */
    private $file;

    public function __construct()
    {
        $this->setActive( TRUE );
        $this->setPosition( 0 );
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
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param Image $image
     *
     * @return Slider
     */
    public function setImage( Image $image = NULL )
    {
        $this->image = $image;

        if ( !empty( $image ) ) {
            $image->hasPublicImages( 'sliders' );
        }

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Slider
     */
    public function setPosition( $position )
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Slider
     */
    public function setActive( $active )
    {
        $this->active = $active;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile( $file )
    {
        $this->file = $file;
    }
}
