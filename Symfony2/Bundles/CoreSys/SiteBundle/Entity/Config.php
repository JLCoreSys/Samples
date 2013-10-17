<?php

namespace CoreSys\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CoreSys\MediaBundle\Entity\Image;

/**
 * Config
 *
 * @ORM\Table(name="site_config")
 * @ORM\Entity(repositoryClass="CoreSys\SiteBundle\Entity\ConfigRepository")
 */
class Config
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
     * @var string
     *
     * @ORM\Column(name="site_name", type="string", length=255, nullable=true)
     */
    private $siteName;

    /**
     * @var string
     *
     * @ORM\Column(name="site_title", type="string", length=255, nullable=true)
     */
    private $siteTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="site_slogan", type="string", length=255, nullable=true)
     */
    private $siteSlogan;

    /**
     * @var string
     *
     * @ORM\Column(name="site_keywords", type="string", length=255, nullable=true)
     */
    private $siteKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="site_description", type="text", nullable=true)
     */
    private $siteDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="site_admin_email", type="string", length=255, nullable=true)
     */
    private $siteAdminEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="site_webmaster_email", type="string", length=255, nullable=true)
     */
    private $siteWebmasterEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="site_support_email", type="string", length=255, nullable=true)
     */
    private $siteSupportEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_invites", type="boolean", nullable=true)
     */
    private $allowInvites;

    /**
     * @var string
     *
     * @ORM\Column(name="terms_of_use_title", type="string", length=255, nullable=true)
     */
    private $termsOfUseTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="terms_of_use", type="text", nullable=true)
     */
    private $termsOfUse;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_policy_title", type="string", length=255, nullable=true)
     */
    private $privacyPolicyTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_policy", type="text", nullable=true)
     */
    private $privacyPolicy;

    /**
     * @var string
     *
     * @ORM\Column(name="about_us_title", type="string", length=255, nullable=true)
     */
    private $aboutUsTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="about_us", type="text", nullable=true)
     */
    private $aboutUs;

    /**
     * @var string
     *
     * @ORM\Column(name="rules_title", type="string", length=255, nullable=true)
     */
    private $rulesTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="rules", type="text", nullable=true)
     */
    private $rules;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_us_title", type="string", length=255, nullable=true)
     */
    private $contactUsTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_us", type="text", nullable=true)
     */
    private $contactUs;

    /**
     * @var string
     *
     * @ORM\Column(name="help_title", type="string", length=255, nullable=true)
     */
    private $helpTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="help", type="text", nullable=true)
     */
    private $help;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state_province", type="string", length=255, nullable=true)
     */
    private $state_province;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_postal", type="string", length=16, nullable=true)
     */
    private $zip_postal;

    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_monday_from", type="string", length=16, nullable=true)
     */
    private $hours_monday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_monday_to", type="string", length=16, nullable=true)
     */
    private $hours_monday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_tuesday_from", type="string", length=16, nullable=true)
     */
    private $hours_tuesday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_tuesday_to", type="string", length=16, nullable=true)
     */
    private $hours_tuesday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_wednesday_from", type="string", length=16, nullable=true)
     */
    private $hours_wednesday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_wednesday_to", type="string", length=16, nullable=true)
     */
    private $hours_wednesday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_thursday_from", type="string", length=16, nullable=true)
     */
    private $hours_thursday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_thursday_to", type="string", length=16, nullable=true)
     */
    private $hours_thursday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_friday_from", type="string", length=16, nullable=true)
     */
    private $hours_friday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_friday_to", type="string", length=16, nullable=true)
     */
    private $hours_friday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_saturday_from", type="string", length=16, nullable=true)
     */
    private $hours_saturday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_saturday_to", type="string", length=16, nullable=true)
     */
    private $hours_saturday_to;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_sunday_from", type="string", length=16, nullable=true)
     */
    private $hours_sunday_from;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_sunday_to", type="string", length=16, nullable=true)
     */
    private $hours_sunday_to;

    /**
     * @var text
     *
     * @ORM\Column(name="footer_content", type="text", nullable=true)
     */
    private $footer_content;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinTable(name="site_config_images",
     *  joinColumns={@ORM\JoinColumn(name="site_config_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")})
     */
    private $images;

    private $files;

    public function __construct()
    {
        $this->setSiteName( 'Default site name' );
        $this->setSiteTitle( 'Default site title' );
        $this->setSiteSlogan( 'Default site slogan' );
        $this->setSiteDescription( 'Default site description.' );
        $this->setSiteKeywords( 'site, sitename' );
        $this->setSiteAdminEmail( 'admin@domain.com' );
        $this->setSiteSupportEmail( 'support@domain.com' );
        $this->setSiteWebmasterEmail( 'webmaster@domain.com' );
        $this->setAllowInvites( TRUE );
        $this->setImages( new ArrayCollection() );
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles( $files )
    {
        $this->files = $files;

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages( $images )
    {
        $this->images = $images;

        return $this;
    }

    public function addImage( Image $image = NULL )
    {
        if ( !$this->images->contains( $image ) ) {
            $this->images->add( $image );
        }

        return $this;
    }

    public function removeImage( Image $image = NULL )
    {
        if ( $this->images->contains( $image ) ) {
            $this->images->removeElement( $image );
        }

        return $this;
    }

    public function getFooterContent()
    {
        return $this->footer_content;
    }

    public function setFooterContent( $content = NULL )
    {
        $this->footer_content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     */
    public function setAddress1( $address1 )
    {
        $this->address1 = $address1;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     */
    public function setAddress2( $address2 )
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity( $city )
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry( $country )
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax( $fax )
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone( $phone )
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getStateProvince()
    {
        return $this->state_province;
    }

    /**
     * @param string $state_province
     */
    public function setStateProvince( $state_province )
    {
        $this->state_province = $state_province;
    }

    /**
     * @return string
     */
    public function getZipPostal()
    {
        return $this->zip_postal;
    }

    /**
     * @param string $zip_postal
     */
    public function setZipPostal( $zip_postal )
    {
        $this->zip_postal = $zip_postal;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo( Image $logo )
    {
        $this->logo = $logo;

        if ( !empty( $logo ) ) {
            $logo->hasPublicImages( 'site' );
        }

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

    public function hasLogo()
    {
        return !empty( $this->logo );
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
     * Get siteName
     *
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * Set siteName
     *
     * @param string $siteName
     *
     * @return Config
     */
    public function setSiteName( $siteName )
    {
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * Get siteTitle
     *
     * @return string
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * Set siteTitle
     *
     * @param string $siteTitle
     *
     * @return Config
     */
    public function setSiteTitle( $siteTitle )
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    /**
     * Get siteSlogan
     *
     * @return string
     */
    public function getSiteSlogan()
    {
        return $this->siteSlogan;
    }

    /**
     * Set siteSlogan
     *
     * @param string $siteSlogan
     *
     * @return Config
     */
    public function setSiteSlogan( $siteSlogan )
    {
        $this->siteSlogan = $siteSlogan;

        return $this;
    }

    /**
     * Get siteKeywords
     *
     * @return string
     */
    public function getSiteKeywords()
    {
        return $this->siteKeywords;
    }

    /**
     * Set siteKeywords
     *
     * @param string $siteKeywords
     *
     * @return Config
     */
    public function setSiteKeywords( $siteKeywords )
    {
        $this->siteKeywords = $siteKeywords;

        return $this;
    }

    /**
     * Get siteDescription
     *
     * @return string
     */
    public function getSiteDescription()
    {
        return $this->siteDescription;
    }

    /**
     * Set siteDescription
     *
     * @param string $siteDescription
     *
     * @return Config
     */
    public function setSiteDescription( $siteDescription )
    {
        $this->siteDescription = $siteDescription;

        return $this;
    }

    /**
     * Get siteAdminEmail
     *
     * @return string
     */
    public function getSiteAdminEmail()
    {
        return $this->siteAdminEmail;
    }

    /**
     * Set siteAdminEmail
     *
     * @param string $siteAdminEmail
     *
     * @return Config
     */
    public function setSiteAdminEmail( $siteAdminEmail )
    {
        $this->siteAdminEmail = $siteAdminEmail;

        return $this;
    }

    /**
     * Get siteWebmasterEmail
     *
     * @return string
     */
    public function getSiteWebmasterEmail()
    {
        return $this->siteWebmasterEmail;
    }

    /**
     * Set siteWebmasterEmail
     *
     * @param string $siteWebmasterEmail
     *
     * @return Config
     */
    public function setSiteWebmasterEmail( $siteWebmasterEmail )
    {
        $this->siteWebmasterEmail = $siteWebmasterEmail;

        return $this;
    }

    /**
     * Get siteSupportEmail
     *
     * @return string
     */
    public function getSiteSupportEmail()
    {
        return $this->siteSupportEmail;
    }

    /**
     * Set siteSupportEmail
     *
     * @param string $siteSupportEmail
     *
     * @return Config
     */
    public function setSiteSupportEmail( $siteSupportEmail )
    {
        $this->siteSupportEmail = $siteSupportEmail;

        return $this;
    }

    /**
     * Get allowInvites
     *
     * @return boolean
     */
    public function getAllowInvites()
    {
        return $this->allowInvites;
    }

    /**
     * Set allowInvites
     *
     * @param boolean $allowInvites
     *
     * @return Config
     */
    public function setAllowInvites( $allowInvites )
    {
        $this->allowInvites = $allowInvites;

        return $this;
    }

    /**
     * Get termsOfUseTitle
     *
     * @return string
     */
    public function getTermsOfUseTitle()
    {
        return $this->termsOfUseTitle;
    }

    /**
     * Set termsOfUseTitle
     *
     * @param string $termsOfUseTitle
     *
     * @return Config
     */
    public function setTermsOfUseTitle( $termsOfUseTitle )
    {
        $this->termsOfUseTitle = $termsOfUseTitle;

        return $this;
    }

    /**
     * Get termsOfUse
     *
     * @return string
     */
    public function getTermsOfUse()
    {
        return $this->termsOfUse;
    }

    /**
     * Set termsOfUse
     *
     * @param string $termsOfUse
     *
     * @return Config
     */
    public function setTermsOfUse( $termsOfUse )
    {
        $this->termsOfUse = $termsOfUse;

        return $this;
    }

    /**
     * Get privacyPolicyTitle
     *
     * @return string
     */
    public function getPrivacyPolicyTitle()
    {
        return $this->privacyPolicyTitle;
    }

    /**
     * Set privacyPolicyTitle
     *
     * @param string $privacyPolicyTitle
     *
     * @return Config
     */
    public function setPrivacyPolicyTitle( $privacyPolicyTitle )
    {
        $this->privacyPolicyTitle = $privacyPolicyTitle;

        return $this;
    }

    /**
     * Get privacyPolicy
     *
     * @return string
     */
    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    /**
     * Set privacyPolicy
     *
     * @param string $privacyPolicy
     *
     * @return Config
     */
    public function setPrivacyPolicy( $privacyPolicy )
    {
        $this->privacyPolicy = $privacyPolicy;

        return $this;
    }

    /**
     * Get aboutUsTitle
     *
     * @return string
     */
    public function getAboutUsTitle()
    {
        return $this->aboutUsTitle;
    }

    /**
     * Set aboutUsTitle
     *
     * @param string $aboutUsTitle
     *
     * @return Config
     */
    public function setAboutUsTitle( $aboutUsTitle )
    {
        $this->aboutUsTitle = $aboutUsTitle;

        return $this;
    }

    /**
     * Get aboutUs
     *
     * @return string
     */
    public function getAboutUs()
    {
        return $this->aboutUs;
    }

    /**
     * Set aboutUs
     *
     * @param string $aboutUs
     *
     * @return Config
     */
    public function setAboutUs( $aboutUs )
    {
        $this->aboutUs = $aboutUs;

        return $this;
    }

    /**
     * Get rulesTitle
     *
     * @return string
     */
    public function getRulesTitle()
    {
        return $this->rulesTitle;
    }

    /**
     * Set rulesTitle
     *
     * @param string $rulesTitle
     *
     * @return Config
     */
    public function setRulesTitle( $rulesTitle )
    {
        $this->rulesTitle = $rulesTitle;

        return $this;
    }

    /**
     * Get rules
     *
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set rules
     *
     * @param string $rules
     *
     * @return Config
     */
    public function setRules( $rules )
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Get contactUsTitle
     *
     * @return string
     */
    public function getContactUsTitle()
    {
        return $this->contactUsTitle;
    }

    /**
     * Set contactUsTitle
     *
     * @param string $contactUsTitle
     *
     * @return Config
     */
    public function setContactUsTitle( $contactUsTitle )
    {
        $this->contactUsTitle = $contactUsTitle;

        return $this;
    }

    /**
     * Get contactUs
     *
     * @return string
     */
    public function getContactUs()
    {
        return $this->contactUs;
    }

    /**
     * Set contactUs
     *
     * @param string $contactUs
     *
     * @return Config
     */
    public function setContactUs( $contactUs )
    {
        $this->contactUs = $contactUs;

        return $this;
    }

    /**
     * Get helpTitle
     *
     * @return string
     */
    public function getHelpTitle()
    {
        return $this->helpTitle;
    }

    /**
     * Set helpTitle
     *
     * @param string $helpTitle
     *
     * @return Config
     */
    public function setHelpTitle( $helpTitle )
    {
        $this->helpTitle = $helpTitle;

        return $this;
    }

    /**
     * Get help
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Set help
     *
     * @param string $help
     *
     * @return Config
     */
    public function setHelp( $help )
    {
        $this->help = $help;

        return $this;
    }

    /**
     * get hours_monday_from
     * @return string
     */
    public function getHoursMondayFrom()
    {
        return $this->hours_monday_from;
    }

    /**
     * set hours_monday_from
     * @return $this
     */
    public function setHoursMondayFrom( $hours_monday_from = NULL )
    {
        $this->hours_monday_from = $hours_monday_from;

        return $this;
    }

    /**
     * get hours_monday_to
     * @return string
     */
    public function getHoursMondayTo()
    {
        return $this->hours_monday_to;
    }

    /**
     * set hours_monday_to
     * @return $this
     */
    public function setHoursMondayTo( $hours_monday_to = NULL )
    {
        $this->hours_monday_to = $hours_monday_to;

        return $this;
    }

    /**
     * get hours_tuesday_from
     * @return string
     */
    public function getHoursTuesdayFrom()
    {
        return $this->hours_tuesday_from;
    }

    /**
     * set hours_tuesday_from
     * @return $this
     */
    public function setHoursTuesdayFrom( $hours_tuesday_from = NULL )
    {
        $this->hours_tuesday_from = $hours_tuesday_from;

        return $this;
    }

    /**
     * get hours_tuesday_to
     * @return string
     */
    public function getHoursTuesdayTo()
    {
        return $this->hours_tuesday_to;
    }

    /**
     * set hours_tuesday_to
     * @return $this
     */
    public function setHoursTuesdayTo( $hours_tuesday_to = NULL )
    {
        $this->hours_tuesday_to = $hours_tuesday_to;

        return $this;
    }

    /**
     * get hours_wednesday_from
     * @return string
     */
    public function getHoursWednesdayFrom()
    {
        return $this->hours_wednesday_from;
    }

    /**
     * set hours_wednesday_from
     * @return $this
     */
    public function setHoursWednesdayFrom( $hours_wednesday_from = NULL )
    {
        $this->hours_wednesday_from = $hours_wednesday_from;

        return $this;
    }

    /**
     * get hours_wednesday_to
     * @return string
     */
    public function getHoursWednesdayTo()
    {
        return $this->hours_wednesday_to;
    }

    /**
     * set hours_wednesday_to
     * @return $this
     */
    public function setHoursWednesdayTo( $hours_wednesday_to = NULL )
    {
        $this->hours_wednesday_to = $hours_wednesday_to;

        return $this;
    }

    /**
     * get hours_thursday_from
     * @return string
     */
    public function getHoursThursdayFrom()
    {
        return $this->hours_thursday_from;
    }

    /**
     * set hours_thursday_from
     * @return $this
     */
    public function setHoursThursdayFrom( $hours_thursday_from = NULL )
    {
        $this->hours_thursday_from = $hours_thursday_from;

        return $this;
    }

    /**
     * get hours_thursday_to
     * @return string
     */
    public function getHoursThursdayTo()
    {
        return $this->hours_thursday_to;
    }

    /**
     * set hours_thursday_to
     * @return $this
     */
    public function setHoursThursdayTo( $hours_thursday_to = NULL )
    {
        $this->hours_thursday_to = $hours_thursday_to;

        return $this;
    }

    /**
     * get hours_friday_from
     * @return string
     */
    public function getHoursFridayFrom()
    {
        return $this->hours_friday_from;
    }

    /**
     * set hours_friday_from
     * @return $this
     */
    public function setHoursFridayFrom( $hours_friday_from = NULL )
    {
        $this->hours_friday_from = $hours_friday_from;

        return $this;
    }

    /**
     * get hours_friday_to
     * @return string
     */
    public function getHoursFridayTo()
    {
        return $this->hours_friday_to;
    }

    /**
     * set hours_friday_to
     * @return $this
     */
    public function setHoursFridayTo( $hours_friday_to = NULL )
    {
        $this->hours_friday_to = $hours_friday_to;

        return $this;
    }

    /**
     * get hours_saturday_from
     * @return string
     */
    public function getHoursSaturdayFrom()
    {
        return $this->hours_saturday_from;
    }

    /**
     * set hours_saturday_from
     * @return $this
     */
    public function setHoursSaturdayFrom( $hours_saturday_from = NULL )
    {
        $this->hours_saturday_from = $hours_saturday_from;

        return $this;
    }

    /**
     * get hours_saturday_to
     * @return string
     */
    public function getHoursSaturdayTo()
    {
        return $this->hours_saturday_to;
    }

    /**
     * set hours_saturday_to
     * @return $this
     */
    public function setHoursSaturdayTo( $hours_saturday_to = NULL )
    {
        $this->hours_saturday_to = $hours_saturday_to;

        return $this;
    }

    /**
     * get hours_sunday_from
     * @return string
     */
    public function getHoursSundayFrom()
    {
        return $this->hours_sunday_from;
    }

    /**
     * set hours_sunday_from
     * @return $this
     */
    public function setHoursSundayFrom( $hours_sunday_from = NULL )
    {
        $this->hours_sunday_from = $hours_sunday_from;

        return $this;
    }

    /**
     * get hours_sunday_to
     * @return string
     */
    public function getHoursSundayTo()
    {
        return $this->hours_sunday_to;
    }

    /**
     * set hours_sunday_to
     * @return $this
     */
    public function setHoursSundayTo( $hours_sunday_to = NULL )
    {
        $this->hours_sunday_to = $hours_sunday_to;

        return $this;
    }
}
