<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

namespace CoreSys\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;

/**
 * Class AccessType
 * @package CoreSys\UserBundle\Form
 */
class AccessType extends AbstractType
{

    /**
     * @var
     */
    private $class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'path', NULL, array( 'attr' => array( 'data-postdesc' => 'The Access Path' ) ) )
            ->add( 'host', NULL, array( 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'OPTIONAL: define a specific host for this access path' ) ) )
            ->add( 'ip', NULL, array( 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'OPTIONAL: define a specific IP for this access path' ) ) )
            ->add( 'anonymous', 'checkbox', array( 'label' => 'Permit Anonymous?', 'required' => FALSE, 'attr' => array() ) )
            ->add( 'channel', NULL, array( 'required' => FALSE, 'label' => 'Required Channel', 'attr' => array( 'class' => 'uniform', 'data-postdesc' => 'The required Channel (http,https) or leave empty for all' ) ) )
            ->add( 'roles', 'entity', array(
        'class'       => 'CoreSys\UserBundle\Entity\Role',
        'property'    => 'name',
        'empty_value' => NULL,
        'required'    => FALSE,
        'multiple'    => TRUE,
        'attr'        => array(
            'class' => 'form-control'
        )
    ) );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
                                    'intention' => 'create',
                                ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coresys_user_access_type';
    }
}