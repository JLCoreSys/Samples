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
 * Class RoleType
 * @package CoreSys\UserBundle\Form
 */
class RoleType extends AbstractType
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
            ->add( 'name', NULL, array( 'attr' => array( 'data-postdesc' => 'The Name for this Role' ) ) )
            ->add( 'color', NULL, array( 'attr' => array( 'data-postdesc' => 'The color for this role.', 'rel' => 'color-picker' ) ) )
            ->add( 'switch', 'checkbox', array( 'required' => FALSE, 'label' => 'Is this user allowed to switch?', 'attr' => array() ) )
            ->add( 'parents', 'entity', array(
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
        return 'coresys_user_role_type';
    }
}