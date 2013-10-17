<?php

namespace CoreSys\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\DataEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

/**
 * Class SettingsType
 * @package CoreSys\SiteBundle\Form
 */
class SettingsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
        ->add( 'siteName', NULL, array(
                'required' => TRUE,
                'label'    => 'Site Name',
                'attr'     => array(
                    'data-postdesc' => 'The name for this website'
                )
            ) )
        ->add( 'siteTitle', NULL, array(
                'required' => TRUE,
                'label'    => 'Site Title',
                'attr'     => array(
                    'data-postdesc' => 'The title for this website'
                )
            ) )
        ->add( 'siteSlogan', NULL, array(
                'required' => TRUE,
                'label'    => 'Site Slogan',
                'attr'     => array(
                    'data-postdesc' => 'The slogan for this website'
                )
            ) )
        ->add( 'siteKeywords', NULL, array(
                'required' => TRUE,
                'label'    => 'Site Keywords',
                'attr'     => array(
                    'data-postdesc' => 'The site keywords, words/phrases separated by commas'
                )
            ) )
        ->add( 'siteAdminEmail', 'email', array(
                'required' => TRUE,
                'label'    => 'Admin Email',
                'attr'     => array(
                    'data-postdesc' => 'The Administrator Email Address'
                )
            ) )
        ->add( 'siteWebmasterEmail', 'email', array(
                'required' => TRUE,
                'label'    => 'Webmaster Email',
                'attr'     => array(
                    'data-postdesc' => 'The Webmaster Email Address'
                )
            ) )
        ->add( 'siteSupportEmail', 'email', array(
                'required' => TRUE,
                'label'    => 'Support Email',
                'attr'     => array(
                    'data-postdesc' => 'The Support Email Address'
                )
            ) )
        ->add( 'siteDescription', 'genemu_tinymce', array(
                'required' => FALSE,
                'label'    => 'Site Description',
                'attr'     => array(
                    'data-postdesc' => 'The description for this website',
                    'rows'          => 30,
                    'width'         => '100%',
                    'class'         => 'input full-width'
                )
            ) )

//            ->add('allowInvites', 'checkbox', array(
//                'required' => false,
//                'label' => 'Allow Invites',
//                'attr' => array(
//                    'class' => 'switch wider',
//                    'data-postdesc' => 'Allow users to invite others to the site?',
//                    'data-text-on' => 'Allow',
//                    'data-text-off' => 'DisAllow'
//                )
//            ))
        ->add( 'termsOfUseTitle', NULL, array(
                'label'    => 'Terms of Use Title',
                'required' => FALSE,
                'attr'     => array(
                    'data-postdesc' => 'The name/title for the sites terms of service.'
                )
            ) )
        ->add( 'termsOfUse', 'genemu_tinymce', array(
                'required' => FALSE,
                'label'    => 'Terms of Use',
                'attr'     => array(
                    'data-postdesc' => 'The Terms of Use for this website.',
                    'rows'          => 30,
                    'width'         => '100%',
                    'class'         => 'input full-width'
                )
            ) )

        ->add( 'privacyPolicyTitle', NULL, array(
                'label'    => 'Privacy Policy Title',
                'required' => FALSE,
                'attr'     => array(
                    'data-postdesc' => 'The name/title for the sites privacy policy.'
                )
            ) )
        ->add( 'privacyPolicy', 'genemu_tinymce', array(
                'required' => FALSE,
                'label'    => 'Privacy Policy',
                'attr'     => array(
                    'data-postdesc' => 'The Privacy Policy for this website.',
                    'rows'          => 30,
                    'width'         => '100%',
                    'class'         => 'input full-width'
                )
            ) )
        ->add( 'file', 'genemu_jqueryfile', array( 'label' => 'Logo', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'The logo to use for the site.' ) ) )
        ->add( 'files', 'genemu_jqueryfile', array( 'label' => 'Site Configuration Images', 'multiple' => TRUE, 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Various images used for site configuration.' ) ) )
        ->add( 'address1', NULL, array( 'label' => 'Address1', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Primary address line one' ) ) )
        ->add( 'address2', NULL, array( 'label' => 'Address2', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Primary address line two' ) ) )
        ->add( 'state_province', NULL, array( 'label' => 'State/Province', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Province/State' ) ) )
        ->add( 'city', NULL, array( 'label' => 'City', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company City' ) ) )
        ->add( 'country', NULL, array( 'label' => 'Country', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Country' ) ) )
        ->add( 'zip_postal', NULL, array( 'label' => 'Zip/Postal', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Zip/Postal' ) ) )
        ->add( 'phone', NULL, array( 'label' => 'Phone Number', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Phone Number' ) ) )
        ->add( 'fax', NULL, array( 'label' => 'Fax Number', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Company Fax Number' ) ) )

        ->add( 'hours_monday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Monday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Monday Hours From' ) ) )
        ->add( 'hours_monday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Monday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Monday Hours To' ) ) )
        ->add( 'hours_tuesday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Tuesday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Tuesday Hours From' ) ) )
        ->add( 'hours_tuesday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Tuesday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Tuesday Hours To' ) ) )
        ->add( 'hours_wednesday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Wednesday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Wednesday Hours From' ) ) )
        ->add( 'hours_wednesday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Wednesday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Wednesday Hours To' ) ) )
        ->add( 'hours_thursday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Thursday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Thursday Hours From' ) ) )
        ->add( 'hours_thursday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Thursday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Thursday Hours To' ) ) )
        ->add( 'hours_friday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Friday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Friday Hours From' ) ) )
        ->add( 'hours_friday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Friday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Friday Hours To' ) ) )
        ->add( 'hours_saturday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Saturday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Saturday Hours From' ) ) )
        ->add( 'hours_saturday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Saturday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Saturday Hours To' ) ) )
        ->add( 'hours_sunday_from', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Sunday Hours From', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Sunday Hours From' ) ) )
        ->add( 'hours_sunday_to', 'genemu_jqueryselect2_choice', array( 'choices' => $this->getTimeOptions(), 'label' => 'Sunday Hours To', 'required' => FALSE, 'attr' => array( 'data-postdesc' => 'Sunday Hours To' ) ) )

        ->add( 'footer_content', 'genemu_tinymce', array(
                'required' => FALSE,
                'label'    => 'Privacy Policy',
                'attr'     => array(
                    'data-postdesc' => 'The static footer content.',
                    'rows'          => 30,
                    'width'         => '100%',
                    'class'         => 'input full-width'
                )
            ) )//            ->add('rulesTitle', null, array(
//                'label' => 'Rules Title',
//                'required' => false,
//                'attr' => array(
//                    'data-postdesc' => 'The name/title for the sites rules.'
//                )
//            ))
//            ->add('rules', 'genemu_tinymce', array(
//                'required' => false,
//                'label' => 'Rules',
//                'attr' => array(
//                    'data-postdesc' => 'The Rules for this website.',
//                    'rows' => 10,
//                    'class' => 'input full-width'
//                )
//            ))
        ;
    }

    /**
     * @return array
     */
    public function getTimeOptions()
    {
        $return = array( 'Gone Riding' => 'Gone Riding', 'Closed' => 'Closed' );
        $hours  = array( 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
        $mins   = array( '00', '15', '30', '45' );
        for ( $i = 0; $i <= 1; $i++ ) {
            $ampm = $i == 0 ? 'am' : 'pm';
            foreach ( $hours as $hour ) {
                foreach ( $mins as $min ) {
                    $time            = $hour . ':' . $min . ' ' . $ampm;
                    $return[ $time ] = $time;
                }
            }
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'settings_type';
    }
}