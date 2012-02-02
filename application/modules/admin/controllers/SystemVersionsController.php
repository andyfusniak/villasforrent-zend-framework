<?php
class Admin_SystemVersionsController extends Zend_Controller_Action
{
    const version = '1.0.0';
    
    public function listAction()
    {
        //
        // frontend
        //
        
        $frontendControllerList = array (
            'AdvertiserAccountController',
            'AdvertiserAuthenticationController',
            'AdvertiserAvailabilityController',
            'AdvertiserContinueController',
            'AdvertiserEmailConfirmationController',
            'AdvertiserImageManagerController',
            'AdvertiserPasswordReminderController',
            'AdvertiserPasswordResetController',
            'AdvertiserPropertyController',
            'AdvertiserRatesController',
            'AdvertiserRegistrationController',
            'AvailabilityImageController',
            'DisplayFullPropertyController',
            'ErrorController',
            'ImageCacheController',
            'IndexController',
            'LevelController'
        );
        
        $frontendActionHelperList = array (
            'DigestKey',
            'EnsureSecure'.
            'FeaturedProperty',
            'LevelSummary'
        );
        
        $frontendViewHelperList = array (
            'GroupSectionTitle',
            'PhotoGrid',
            'SectionTitle'
        );
        
        //
        // common
        //
        
        //
        // admin
        //
        
        $adminControllerList = array (
            'AdvertiserController',
            'AuthFailController',
            'AutoLoginController',
            'FeaturedController',
            'IndexController',
            'PropertyController',
            'SystemCheckController',
            'SystemVersionsController'
        );
        
        $pathList = array (    
        );
        
        //
        // Vfr core
        //
        
        $versions = array (
            'Vfr_Availability_Calendar_ImagePng' => Vfr_Availability_Calendar_ImagePng::version,
            'Vfr_Availability_Calendar_Object'   => Vfr_Availability_Calendar_Object::version,
            'Vfr_BlowfishHasher'                 => Vfr_BlowfishHasher::version,
            'Vfr_Property_Converter'             => Vfr_Property_Converter::version,
            
            // Vfr core valdiators
            'Vfr_Validate_AvailabilityRange'     => Vfr_Validate_AvailabilityRange::version,
            'Vfr_Validate_EmailCheck'            => Vfr_Validate_EmailCheck::version,
            'Vfr_Validate_PasswordConfirmation'  => Vfr_Validate_PasswordConfirmation::version,
            'Vfr_Validate_PropertyUrl'           => Vfr_Validate_PropertyUrl::version,
            'Vfr_Validate_RatesRange'            => Vfr_Validate_RatesRange::version,
            'Vfr_Validate_UniqueAdvertiserEmail' => Vfr_Validate_UniqueAdvertiserEmail::version,
            
            // Vfr core view helpers
            'Vfr_View_Helper_AdvertiserControlPanelStatus' => Vfr_View_Helper_AdvertiserControlPanelStatus::version,
            'Vfr_View_Helper_AspectRatio'                  => Vfr_View_Helper_AspectRatio::version,
            'Vfr_View_Helper_AvailabilityNotifier'         => Vfr_View_Helper_AvailabilityNotifier::version,
            'Vfr_View_Helper_AvailabilityPicker' => Vfr_View_Helper_AvailabilityPicker::version,
            'Vfr_View_Helper_BaseCurrency' => Vfr_View_Helper_BaseCurrency::version,
            'Vfr_View_Helper_Bytes' => Vfr_View_Helper_Bytes::version,
            'Vfr_View_Helper_DisplayErrors' => Vfr_View_Helper_DisplayErrors::version,
            'Vfr_View_Helper_FormatCurrency' => Vfr_View_Helper_FormatCurrency::version,
            'Vfr_View_Helper_HolidayType' => Vfr_View_Helper_HolidayType::version,
            'Vfr_View_Helper_ImageConfirmDeleteButton' => Vfr_View_Helper_ImageConfirmDeleteButton::version,
            'Vfr_View_Helper_ImageMoveButtons' => Vfr_View_Helper_ImageMoveButtons::version,
            'Vfr_View_Helper_ImageUploadNotifier' => Vfr_View_Helper_ImageUploadNotifier::version,
            'Vfr_View_Helper_LocationBreadcrumb' => Vfr_View_Helper_LocationBreadcrumb::version,
            'Vfr_View_Helper_MinStayDuration' => Vfr_View_Helper_MinStayDuration::version,
            'Vfr_View_Helper_Photo' => Vfr_View_Helper_Photo::version,
            'Vfr_View_Helper_PrettyDate' => Vfr_View_Helper_PrettyDate::version,
            'Vfr_View_Helper_PropertyTinyThumb' => Vfr_View_Helper_PropertyTinyThumb::version,
            'Vfr_View_Helper_PropertyType' => Vfr_View_Helper_PropertyType::version,
            'Vfr_View_Helper_RateCurrency' => Vfr_View_Helper_RateCurrency::version,
            'Vfr_View_Helper_RatesNotifier' => Vfr_View_Helper_RatesNotifier::version,
            'Vfr_View_Helper_RatesPicker' => Vfr_View_Helper_RatesPicker::version,
            'Vfr_View_Helper_RentalBasis' => Vfr_View_Helper_RentalBasis::version,
            'Vfr_View_Helper_SummaryBar' => Vfr_View_Helper_SummaryBar::version,
            'Vfr_View_Helper_TinyThumb' => Vfr_View_Helper_TinyThumb::version,
            'Vfr_View_Helper_WebsiteUrl' => Vfr_View_Helper_WebsiteUrl::version
        );
        
        $this->view->assign(
            array (
                'versions' => $versions
            )
        );
        
        //var_dump(Frontend_Helper_DigestKey::version);
        
        var_dump(Vfr_Availability_Calendar_ImagePng::version);
        
        //var_dump(Admin_AuthFailController::version);
        
    }
}