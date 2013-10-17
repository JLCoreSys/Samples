<?php

namespace CoreSys\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;

class UserProfileType extends AbstractType
{

    private $class;

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $this->buildUserForm( $builder, $options );

        $builder->add( 'current_password', 'repeated', array(
            'type'               => 'password',
            'label'              => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'invalid_message'    => 'The password fields must match',
            'mapped'             => FALSE,
            'constraints'        => new UserPassword(),
            'required'           => FALSE,
            'options'            => array( 'required' => FALSE, 'label' => 'Password', 'attr' => array( 'data-postdesc' => '<span class="gold">Only enter a password if you want to change your current password. Leave blank to keep password.</span>' ) ),
        ) );
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
//            'data_class' => $this->class,
                                    'intention' => 'profile',
                                ) );
    }

    public function getName()
    {
        return 'coresys_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm( FormBuilderInterface $builder, array $options )
    {
        $builder
        ->add( 'username', NULL, array( 'label' => 'form.username', 'translation_domain' => 'FOSUserBundle', 'read_only' => TRUE, 'attr' => array( 'data-postdesc' => '<span class="gold">Usernames cannot be changed.</span>' ) ) )
        ->add( 'email', 'email', array( 'label' => 'form.email', 'translation_domain' => 'FOSUserBundle' ) )
        ->add( 'first_name', NULL, array( 'required' => TRUE ) )
        ->add( 'last_name', NULL, array( 'required' => TRUE ) );
    }
}