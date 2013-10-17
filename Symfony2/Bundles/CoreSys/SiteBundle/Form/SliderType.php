<?php

namespace CoreSys\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\DataEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

/**
 * Class SliderType
 * @package CoreSys\SiteBundle\Form
 */
class SliderType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
        ->add( 'file', 'genemu_jqueryfile', array( 'label' => 'Slider Image', 'required' => FALSE, 'multiple' => TRUE, 'attr' => array( 'data-postdesc' => 'The slider image.' ) ) )
        ->add( 'active', 'checkbox', array( 'required' => FALSE, 'attr' => array( 'class' => 'switch wider', 'data-postdesc' => 'Is this slider active?' ) ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'slider_type';
    }
}