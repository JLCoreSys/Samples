<?php
/**
 * This file is part of the CoreSysUserBundle package.
 * (c) J&L Core Systems http://jlcoresystems.com | http://joshmccreight.com
 */

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//namespace FOS\UserBundle\Form\Type;
namespace CoreSys\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;

/**
 * Class UserRegisterType
 * @package CoreSys\UserBundle\Form
 */
class UserRegisterType extends AbstractType
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
        $this->buildUserForm( $builder, $options );
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
        return 'coresys_user_register_type';
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
            ->add( 'username', NULL, array( 'label' => 'form.username', 'translation_domain' => 'FOSUserBundle',
                                            'attr'  => array( 'data-postdesc' => 'The desired username' ) ) )
            ->add( 'email', 'email', array( 'label' => 'form.email', 'translation_domain' => 'FOSUserBundle',
                                            'attr'  => array( 'data-postdesc' => 'The email address for this user.' ) ) )
            ->add( 'plainPassword', 'repeated', array(
                'type'            => 'password',
                'options'         => array( 'translation_domain' => 'FOSUserBundle',
                                            'attr'               => array( 'data-postdesc' => 'Password and confirm-password' ) ),
                'first_options'   => array( 'label' => 'form.password' ),
                'second_options'  => array( 'label' => 'form.password_confirmation' ),
                'invalid_message' => 'fos_user.password.mismatch' ) )
            ->add( 'first_name', NULL,
                   array( 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'The users first name' ) ) )
            ->add( 'last_name', NULL,
                   array( 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'The users last name' ) ) );
    }
}